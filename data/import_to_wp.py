import json
import requests
from requests.auth import HTTPBasicAuth
import sys
import os

"""
WordPress REST API Batch Importer
Usage:
    python import_to_wp.py <SITE_URL> <USERNAME> <APPLICATION_PASSWORD>

Example:
    python import_to_wp.py http://localhost:8000 admin "xxxx xxxx xxxx xxxx"
"""

def main():
    if len(sys.argv) < 4:
        print("Usage: python import_to_wp.py <SITE_URL> <USERNAME> <APPLICATION_PASSWORD>")
        print("Example: python import_to_wp.py http://localhost:8000 admin 'xxxx xxxx xxxx'")
        sys.exit(1)

    site_url = sys.argv[1].rstrip("/")
    username = sys.argv[2]
    app_password = sys.argv[3]
    
    api_url = f"{site_url}/wp-json/wp/v2"
    auth = HTTPBasicAuth(username, app_password)

    data_dir = os.path.dirname(os.path.abspath(__file__))

    # Load data
    with open(os.path.join(data_dir, "categories.json"), "r", encoding="utf-8") as f:
        categories = json.load(f)

    with open(os.path.join(data_dir, "tags.json"), "r", encoding="utf-8") as f:
        tags = json.load(f)

    with open(os.path.join(data_dir, "posts.json"), "r", encoding="utf-8") as f:
        posts = json.load(f)

    print(f"Connecting to target site: {site_url}")
    
    # Test connection
    res = requests.get(f"{api_url}/users/me", auth=auth)
    if res.status_code != 200:
        print(f"Error authenticating with WordPress REST API: {res.status_code} - {res.text}")
        sys.exit(1)
    print(f"Authenticated successfully as: {res.json().get('name')}")

    # 1. Post Categories
    print("\n--- Importing Categories ---")
    cat_id_mapping = {}
    for cat in categories:
        cat_data = {
            "name": cat["name"],
            "slug": cat["slug"],
            "description": cat.get("description", "")
        }
        res = requests.post(f"{api_url}/categories", json=cat_data, auth=auth)
        if res.status_code in (200, 201):
            new_id = res.json()["id"]
            cat_id_mapping[cat["id"]] = new_id
            print(f"[OK] Category: {cat['name']} -> ID {new_id}")
        else:
            # If already exists, fetch existing
            res_exist = requests.get(f"{api_url}/categories?slug={cat['slug']}", auth=auth)
            if res_exist.status_code == 200 and res_exist.json():
                existing_id = res_exist.json()[0]["id"]
                cat_id_mapping[cat["id"]] = existing_id
                print(f"[EXISTS] Category: {cat['name']} -> ID {existing_id}")
            else:
                print(f"[FAIL] Category: {cat['name']}: {res.text}")

    # 2. Post Tags
    print("\n--- Importing Tags ---")
    tag_id_mapping = {}
    for tag in tags:
        tag_data = {
            "name": tag["name"],
            "slug": tag["slug"],
            "description": tag.get("description", "")
        }
        res = requests.post(f"{api_url}/tags", json=tag_data, auth=auth)
        if res.status_code in (200, 201):
            new_id = res.json()["id"]
            tag_id_mapping[tag["id"]] = new_id
            print(f"[OK] Tag: {tag['name']} -> ID {new_id}")
        else:
            res_exist = requests.get(f"{api_url}/tags?slug={tag['slug']}", auth=auth)
            if res_exist.status_code == 200 and res_exist.json():
                existing_id = res_exist.json()[0]["id"]
                tag_id_mapping[tag["id"]] = existing_id
                print(f"[EXISTS] Tag: {tag['name']} -> ID {existing_id}")
            else:
                print(f"[FAIL] Tag: {tag['name']}: {res.text}")

    # 3. Post 30 Posts
    print("\n--- Importing 30 Posts ---")
    for p in posts:
        post_categories = [cat_id_mapping[cid] for cid in p.get("categories", []) if cid in cat_id_mapping]
        post_tags = [tag_id_mapping[tid] for tid in p.get("tags", []) if tid in tag_id_mapping]

        post_payload = {
            "title": p.get("title", {}).get("rendered", ""),
            "content": p.get("content", {}).get("rendered", ""),
            "excerpt": p.get("excerpt", {}).get("rendered", ""),
            "slug": p.get("slug", ""),
            "status": "publish",
            "categories": post_categories,
            "tags": post_tags
        }

        res = requests.post(f"{api_url}/posts", json=post_payload, auth=auth)
        if res.status_code in (200, 201):
            print(f"[OK] Post Created: {p.get('title', {}).get('rendered', '')[:50]}")
        else:
            print(f"[FAIL] Post: {res.status_code} - {res.text}")

    print("\nAll items imported successfully!")

if __name__ == "__main__":
    main()

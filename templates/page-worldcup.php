<?php
/*
Template Name: World Cup Fixtures
*/

get_header(); 
?>

<style>
    :root {
        --bd-red: #F70505;
        --bd-red-hover: #cc0000;
        --bd-peach: #FFF1E0;
        --bd-gray: #121111;
        --bd-light-gray: #f8fafc;
        --bd-border: #e2e8f0;
        --bd-text: #334155;
        --bd-text-muted: #64748b;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .wc-container {
        max-width: 1250px;
        margin: 0 auto;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        color: var(--bd-gray);
        padding: 20px;
    }

    /* Premium Header */
    .wc-header {
        background: linear-gradient(135deg, var(--bd-gray) 0%, #2a2a2a 100%);
        padding: 40px 30px;
        border-radius: 16px;
        text-align: center;
        border-bottom: 6px solid var(--bd-red);
        margin-bottom: 40px;
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
    }
    
    .wc-header::before {
        content: '';
        position: absolute;
        top: -50px; left: -50px; width: 150px; height: 150px;
        background: radial-gradient(circle, rgba(247,5,5,0.2) 0%, transparent 70%);
        border-radius: 50%;
    }

    .wc-header h1 {
        margin: 0;
        font-size: 36px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: -0.5px;
        color: #ffffff;
    }

    .wc-header p {
        margin: 10px 0 0;
        color: var(--bd-peach);
        font-size: 16px;
        font-weight: 500;
        letter-spacing: 1px;
    }

    /* Tabs & Filters */
    .wc-controls {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 20px;
        margin-bottom: 30px;
    }

    .wc-tabs {
        display: flex;
        justify-content: center;
        background: var(--bd-light-gray);
        padding: 6px;
        border-radius: 40px;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
        flex-wrap: wrap;
    }

    .wc-tab {
        background: transparent;
        border: none;
        padding: 12px 26px;
        border-radius: 30px;
        font-size: 15px;
        font-weight: 700;
        color: var(--bd-text-muted);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .wc-tab:hover {
        color: var(--bd-gray);
    }

    .wc-tab.active {
        background: #ffffff;
        color: var(--bd-red);
        box-shadow: var(--shadow-sm);
    }

    /* Sub-filters for Groups */
    .wc-filters {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
        opacity: 0;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        visibility: hidden;
        height: 0;
        overflow: hidden;
    }
    
    .wc-filters.show {
        opacity: 1;
        transform: translateY(0);
        visibility: visible;
        height: auto;
        margin-bottom: 20px;
    }

    .wc-filter {
        background: #fff;
        border: 1px solid var(--bd-border);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        color: var(--bd-text);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .wc-filter:hover {
        border-color: var(--bd-text-muted);
    }
    
    .wc-filter.active {
        background: var(--bd-gray);
        color: #fff;
        border-color: var(--bd-gray);
    }

    /* Content & Loading */
    .wc-loading {
        text-align: center;
        padding: 50px;
        font-size: 18px;
        font-weight: 600;
        color: var(--bd-text-muted);
    }
    
    .wc-loading .spinner {
        border: 3px solid var(--bd-border);
        border-top: 3px solid var(--bd-red);
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin: 0 auto 15px;
    }

    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    /* Match List & Animations (Standard Tabs) */
    .wc-date-group {
        margin-bottom: 40px;
        animation: fadeIn 0.5s ease forwards;
        opacity: 0;
    }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .wc-date-header {
        background: var(--bd-light-gray);
        color: var(--bd-text);
        padding: 12px 20px;
        font-size: 15px;
        font-weight: 700;
        border-radius: 8px;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-left: 4px solid var(--bd-red);
    }

    .wc-match {
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid var(--bd-border);
        border-radius: 12px;
        margin-bottom: 15px;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        overflow: hidden;
    }

    .wc-match:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
        border-color: #cbd5e1;
    }

    .wc-match-main {
        display: flex;
        align-items: center;
        padding: 20px;
    }

    .wc-match-time {
        width: 90px;
        font-size: 14px;
        font-weight: 600;
        color: var(--bd-text-muted);
        display: flex;
        flex-direction: column;
        border-right: 1px solid var(--bd-border);
        padding-right: 15px;
    }
    
    .wc-match-status {
        font-size: 11px;
        text-transform: uppercase;
        margin-top: 6px;
        font-weight: 800;
        letter-spacing: 0.5px;
    }
    
    .wc-status-live {
        color: var(--bd-red);
        animation: pulseLive 1.5s infinite;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .wc-status-live::before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 6px;
        background: var(--bd-red);
        border-radius: 50%;
    }
    
    .wc-status-ft { color: var(--bd-gray); }

    @keyframes pulseLive { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }

    .wc-match-teams {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 20px;
        padding: 0 15px;
    }

    .wc-team {
        flex: 1;
        font-size: 18px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .wc-team.home { justify-content: flex-end; text-align: right; }
    .wc-team.away { justify-content: flex-start; text-align: left; }
    
    .wc-team-flag {
        width: 36px;
        height: 26px;
        background: var(--bd-light-gray);
        border: 1px solid rgba(0,0,0,0.1);
        border-radius: 4px;
        overflow: hidden;
        display: inline-block;
        background-size: cover;
        background-position: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    .wc-score-box {
        background: var(--bd-gray);
        color: #fff;
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 22px;
        font-weight: 900;
        letter-spacing: 2px;
        min-width: 80px;
        text-align: center;
        box-shadow: inset 0 -2px 0 rgba(0,0,0,0.2);
    }
    
    .wc-score-box.not-started {
        background: var(--bd-light-gray);
        color: var(--bd-text-muted);
        border: 1px solid var(--bd-border);
        font-size: 16px;
        font-weight: 700;
        letter-spacing: normal;
        box-shadow: none;
    }

    .wc-match-chevron {
        width: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: var(--bd-border);
        transition: transform 0.3s ease, color 0.3s ease;
    }
    
    .wc-match:hover .wc-match-chevron { color: var(--bd-text); }
    .wc-match.expanded .wc-match-chevron { transform: rotate(180deg); color: var(--bd-red); }

    .wc-match-details {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: #fafafa;
        border-top: 1px solid transparent;
    }
    
    .wc-match.expanded .wc-match-details { max-height: 300px; border-top-color: var(--bd-border); }
    
    .wc-details-inner { padding: 20px; display: flex; flex-direction: column; gap: 15px; }

    .wc-meta-bar {
        display: flex; justify-content: space-between; font-size: 13px; color: var(--bd-text-muted);
        background: #fff; padding: 10px 15px; border-radius: 6px; border: 1px solid var(--bd-border);
    }

    .wc-meta-bar strong { color: var(--bd-gray); }

    .wc-scorers { display: flex; justify-content: space-between; font-size: 13px; color: var(--bd-text); font-weight: 500; }
    .wc-scorers-home { text-align: right; flex: 1; padding-right: 20px; }
    .wc-scorers-away { text-align: left; flex: 1; padding-left: 20px; }
    .wc-scorer-item { display: flex; align-items: center; gap: 6px; margin-bottom: 4px; }
    .wc-scorers-home .wc-scorer-item { justify-content: flex-end; }
    .wc-goal-icon {
        display: inline-block; width: 12px; height: 12px;
        background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="%23121111" d="M256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM203 76.5c16.9-10.2 36.6-16 57.5-16s40.6 5.8 57.5 16l-37 114H240L203 76.5zM76.5 203c10.2-16.9 23.6-31.5 39.5-42.8l70 102.3-90.4 65.7C84.3 306 75.8 281.8 76.5 203zM256 451.5c-19.1 0-37.3-5-52.9-13.8L256 313.3l52.9 124.4c-15.6 8.8-33.8 13.8-52.9 13.8zM416 328.1l-90.4-65.7 70-102.3c15.8 11.3 29.2 25.9 39.5 42.8-.7 78.8-9.2 103-19.1 125.2zM256 166.5L309 330H203l53-163.5z"/></svg>') no-repeat center;
    }

    /* 
    ======================================
    PREDICTOR BRACKET STYLES
    ======================================
    */
    .wc-bracket-wrapper {
        overflow-x: auto;
        padding: 5px 0 40px;
        animation: fadeIn 0.5s ease forwards;
        scrollbar-width: thin;
        scrollbar-color: var(--bd-red) var(--bd-light-gray);
    }
    .wc-bracket-wrapper::-webkit-scrollbar { height: 8px; }
    .wc-bracket-wrapper::-webkit-scrollbar-track { background: var(--bd-light-gray); border-radius: 4px; }
    .wc-bracket-wrapper::-webkit-scrollbar-thumb { background-color: var(--bd-red); border-radius: 4px; }

    .wc-bracket-container {
        display: flex;
        min-width: 1200px;
        gap: 40px;
        justify-content: flex-start;
        align-items: center;
        padding-bottom: 20px;
    }

    .wc-bracket-round {
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        flex: 1;
        gap: 20px;
        min-height: 800px;
        position: relative;
    }

    .wc-bracket-round-title {
        text-align: center;
        font-size: 14px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--bd-text-muted);
        margin-bottom: 20px;
        height: 20px;
    }

    .wc-pmatch {
        background: #fff;
        border: 2px solid var(--bd-border);
        border-radius: 10px;
        box-shadow: var(--shadow-sm);
        display: flex;
        flex-direction: column;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 2;
    }
    
    .wc-pmatch:hover {
        border-color: #cbd5e1;
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    .wc-pteam {
        display: flex;
        align-items: center;
        padding: 10px 14px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 1px solid var(--bd-border);
    }

    .wc-pteam::before {
        content: '';
        display: inline-block;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 2px solid var(--bd-border);
        margin-right: 10px;
        transition: all 0.2s ease;
        flex-shrink: 0;
        background: #fff;
    }

    .wc-pteam:last-child {
        border-bottom: none;
    }

    .wc-pteam:hover {
        background-color: var(--bd-light-gray);
    }

    .wc-pteam.winner {
        background-color: var(--bd-peach);
        color: var(--bd-red);
        font-weight: 800;
    }

    .wc-pteam.winner::before {
        background: var(--bd-red);
        border-color: var(--bd-red);
        box-shadow: inset 0 0 0 3px var(--bd-peach);
    }

    .wc-pteam.knocked-out {
        opacity: 0.4;
        filter: grayscale(100%);
        text-decoration: line-through;
    }

    .wc-pteam.knocked-out:hover {
        opacity: 0.8;
        filter: none;
        text-decoration: none;
    }

    .wc-pteam.empty {
        color: var(--bd-text-muted);
        font-weight: 400;
        font-style: italic;
        cursor: default;
        border: 2px dashed var(--bd-border);
        background: rgba(0,0,0,0.02);
        margin: 4px;
        border-radius: 6px;
    }

    .wc-pteam.empty::before {
        display: none;
    }

    .wc-pteam.empty:hover {
        background-color: transparent;
    }

    .wc-pmatch-champ {
        text-align: center;
        padding: 30px;
        background: var(--bd-red);
        color: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 20px rgba(247, 5, 5, 0.3);
        transform: scale(1.1);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        animation: pulseChamp 2s infinite;
    }

    @keyframes pulseChamp {
        0% { box-shadow: 0 0 0 0 rgba(247, 5, 5, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(247, 5, 5, 0); }
        100% { box-shadow: 0 0 0 0 rgba(247, 5, 5, 0); }
    }

    .wc-pmatch-champ h3 {
        margin: 0 0 10px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 2px;
        opacity: 0.9;
    }

    .wc-pmatch-champ .champion-name {
        font-size: 24px;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .wc-pteam-flag {
        width: 24px;
        height: 18px;
        background: var(--bd-border);
        border-radius: 3px;
        background-size: cover;
        background-position: center;
        flex-shrink: 0;
        margin-right: 8px;
    }

    .wc-pselect {
        border: none;
        background: transparent;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        color: inherit;
        outline: none;
        cursor: pointer;
        width: 100%;
        appearance: none;
    }
    .wc-pselect:focus { color: var(--bd-red); }
    
    .wc-submission-box {
        margin-top: 30px;
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: var(--shadow-md);
        border-top: 4px solid var(--bd-red);
        text-align: center;
        animation: fadeIn 0.5s ease;
    }
    .wc-submission-box h4 { margin: 0 0 10px; font-size: 18px; color: var(--bd-gray); }
    .wc-submission-box p { font-size: 13px; color: var(--bd-text-muted); margin-bottom: 15px; }
    .wc-submission-box input {
        width: 100%;
        padding: 10px 15px;
        margin-bottom: 10px;
        border: 1px solid var(--bd-border);
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
    }
    .wc-submission-box button {
        width: 100%;
        background: var(--bd-red);
        color: #fff;
        border: none;
        padding: 12px;
        border-radius: 6px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
    }
    .wc-submission-box button:hover { background: var(--bd-red-hover); }
    #pred_msg { margin-top: 10px; font-size: 13px; font-weight: 600; }
    #pred_msg.success { color: green; }
    #pred_msg.error { color: var(--bd-red); }

    .wc-reset-btn {
        background: #fff;
        color: var(--bd-text-muted);
        border: 1px solid var(--bd-border);
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .wc-reset-btn:hover {
        color: var(--bd-red);
        border-color: var(--bd-red);
    }

    @media (max-width: 768px) {
        .wc-match-main { flex-direction: column; align-items: stretch; padding: 15px; }
        .wc-match-time { width: 100%; flex-direction: row; justify-content: space-between; border-right: none; border-bottom: 1px solid var(--bd-border); padding-right: 0; padding-bottom: 12px; margin-bottom: 15px; }
        .wc-match-teams { flex-direction: column; gap: 15px; padding: 0; }
        .wc-team.home, .wc-team.away { justify-content: space-between; text-align: left; flex-direction: row-reverse; width: 100%; }
        .wc-score-box { align-self: center; width: 100%; margin: 5px 0; }
        .wc-match-chevron { display: none; }
        .wc-scorers { flex-direction: column; gap: 10px; }
        .wc-scorers-home, .wc-scorers-away { text-align: left; padding: 0; }
        .wc-scorers-home .wc-scorer-item { justify-content: flex-start; }
    }
</style>

<div class="wc-container">
    <div class="wc-header">
        <h1>World Cup 2026</h1>
        <p>Live Fixtures, Results & Interactive Predictor</p>
    </div>

    <div class="wc-controls">
        <div class="wc-tabs">
            <button class="wc-tab active" data-filter="group">Group Stages</button>
            <button class="wc-tab" data-filter="knockout">Knockout Stages</button>
            <button class="wc-tab" data-filter="predictor" style="border: 2px solid var(--bd-red); color: var(--bd-red);">🏆 Predictor</button>
        </div>
        
        <div class="wc-filters" id="wc-group-filters">
            <button class="wc-filter active" data-group="all">All Groups</button>
        </div>
    </div>

    <div id="wc-content">
        <div class="wc-loading">
            <div class="spinner"></div>
            Connecting to Live Feeds...
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const API_URL = 'https://worldcup26.ir/get/games';
    let allGames = [];
    let currentTab = 'group';
    let currentGroupFilter = 'all';

    // Flag Map
    const flagMap = {
        "Mexico": "mx", "South Africa": "za", "South Korea": "kr", "Czech Republic": "cz",
        "Canada": "ca", "Bosnia and Herzegovina": "ba", "United States": "us", "Paraguay": "py",
        "Haiti": "ht", "Scotland": "gb-sct", "Australia": "au", "Turkey": "tr",
        "Brazil": "br", "Morocco": "ma", "Qatar": "qa", "Switzerland": "ch",
        "Ivory Coast": "ci", "Ecuador": "ec", "Germany": "de", "Curaçao": "cw",
        "Netherlands": "nl", "Japan": "jp", "Sweden": "se", "Tunisia": "tn",
        "Iran": "ir", "New Zealand": "nz", "Spain": "es", "Cape Verde": "cv",
        "Belgium": "be", "Egypt": "eg", "Saudi Arabia": "sa", "Uruguay": "uy",
        "France": "fr", "Senegal": "sn", "Iraq": "iq", "Norway": "no",
        "Argentina": "ar", "Algeria": "dz", "Austria": "at", "Jordan": "jo",
        "Portugal": "pt", "Democratic Republic of the Congo": "cd", "England": "gb-eng",
        "Croatia": "hr", "Uzbekistan": "uz", "Colombia": "co", "Ghana": "gh", "Panama": "pa"
    };

    let teamsByGroup = {};
    let selectOptionsHTML = '<option value="">Select Team...</option>';

    function extractTeamsFromAPI() {
        allGames.filter(g => g.type === 'group').forEach(g => {
            if(!teamsByGroup[g.group]) teamsByGroup[g.group] = new Set();
            if(g.home_team_name_en) teamsByGroup[g.group].add(g.home_team_name_en);
            if(g.away_team_name_en) teamsByGroup[g.group].add(g.away_team_name_en);
        });
        
        Object.keys(teamsByGroup).sort().forEach(grp => {
            let teamsArr = Array.from(teamsByGroup[grp]).sort();
            if(grp !== 'null' && teamsArr.length > 0) {
                selectOptionsHTML += `<optgroup label="Group ${grp}">`;
                teamsArr.forEach(t => {
                    selectOptionsHTML += `<option value="${t}">${t}</option>`;
                });
                selectOptionsHTML += `</optgroup>`;
            }
        });
    }

    // Predictor State Machine
    let bracketState = {
        r32: Array(16).fill(null).map(() => ({ home: '', away: '', apiHome: null, apiAway: null, winner: null })),
        r16: Array(8).fill(null).map(() => ({ home: '', away: '', winner: null })),
        qf: Array(4).fill(null).map(() => ({ home: '', away: '', winner: null })),
        sf: Array(2).fill(null).map(() => ({ home: '', away: '', winner: null })),
        final: Array(1).fill(null).map(() => ({ home: '', away: '', winner: null })),
        champion: null
    };      
    
    function initBracketFromAPI() {
        if(!allGames || allGames.length === 0) return;
        
        const r32Games = allGames.filter(g => g.type === 'r32').sort((a,b) => parseInt(a.id) - parseInt(b.id));
        r32Games.forEach((g, i) => {
            if(i < 16) {
                bracketState.r32[i].apiHome = g.home_team_name_en || null;
                bracketState.r32[i].apiAway = g.away_team_name_en || null;
                // Only overwrite if no manual selection has been made, or if API provides the actual team
                if (!bracketState.r32[i].home || bracketState.r32[i].apiHome) {
                    bracketState.r32[i].home = g.home_team_name_en || g.home_team_label || `Winner G-${i+1}`;
                }
                if (!bracketState.r32[i].away || bracketState.r32[i].apiAway) {
                    bracketState.r32[i].away = g.away_team_name_en || g.away_team_label || `Runner-up G-${i+1}`;
                }
            }
        });
        
        renderPredictor();
    }

    // --- Helpers ---
    function parseGameDate(dateStr) {
        if(!dateStr) return new Date();
        const parts = dateStr.split(' ');
        const dateParts = parts[0].split('/');
        const timeParts = parts[1] ? parts[1].split(':') : ['00','00'];
        return new Date(dateParts[2], dateParts[0] - 1, dateParts[1], timeParts[0], timeParts[1]);
    }
    function formatDateHeader(date) { return date.toLocaleDateString('en-GB', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' }); }
    function formatTime(date) { return date.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' }); }
    function parseScorers(scorerString) {
        if (!scorerString || scorerString === 'null') return [];
        try {
            let cleaned = scorerString.replace(/“/g, '"').replace(/”/g, '"').replace(/’/g, "'");
            const arr = JSON.parse(cleaned);
            if (Array.isArray(arr)) return arr;
            return [cleaned];
        } catch(e) { return [scorerString.replace(/[{}"“\\]/g, '')]; }
    }
    function buildScorersHTML(arr) {
        if(!arr.length) return '';
        return arr.map(s => `<div class="wc-scorer-item"><span class="wc-goal-icon"></span>${s}</div>`).join('');
    }
    function getFlagSpan(teamNameEn, large=false) {
        if(flagMap[teamNameEn]) {
            return `<span class="${large ? 'wc-team-flag' : 'wc-pteam-flag'}" style="background-image: url('https://flagcdn.com/w40/${flagMap[teamNameEn]}.png');"></span>`;
        }
        return `<span class="${large ? 'wc-team-flag' : 'wc-pteam-flag'}" style="background-color: var(--bd-border)"></span>`;
    }

    // --- Filters & Tabs ---
    function populateGroupFilters() {
        const filtersContainer = document.getElementById('wc-group-filters');
        if(currentTab !== 'group') {
            filtersContainer.classList.remove('show');
            return;
        }
        const groups = [...new Set(allGames.filter(g => g.type === 'group').map(g => g.group))].sort();
        let html = `<button class="wc-filter ${currentGroupFilter === 'all' ? 'active' : ''}" data-group="all">All Groups</button>`;
        groups.forEach(g => {
            if(g && g !== 'null' && !['R32','R16','QF','SF','FINAL','3RD'].includes(g)) {
                html += `<button class="wc-filter ${currentGroupFilter === g ? 'active' : ''}" data-group="${g}">Group ${g}</button>`;
            }
        });
        filtersContainer.innerHTML = html;
        filtersContainer.classList.add('show');
        filtersContainer.querySelectorAll('.wc-filter').forEach(btn => {
            btn.addEventListener('click', (e) => {
                currentGroupFilter = e.target.getAttribute('data-group');
                populateGroupFilters();
                renderGames();
            });
        });
    }

    // --- Core Rendering ---
    function renderGames() {
        if(currentTab === 'predictor') {
            renderPredictor();
            return;
        }

        const contentDiv = document.getElementById('wc-content');
        contentDiv.innerHTML = '';

        let filtered = allGames.filter(g => {
            if (currentTab === 'group') {
                if (g.type !== 'group') return false;
                if (currentGroupFilter !== 'all' && g.group !== currentGroupFilter) return false;
                return true;
            } else {
                return g.type !== 'group';
            }
        });

        if (filtered.length === 0) {
            contentDiv.innerHTML = '<div class="wc-loading">No matches found.</div>';
            return;
        }

        const grouped = {};
        filtered.forEach(g => {
            const dateObj = parseGameDate(g.local_date);
            const dateKey = formatDateHeader(dateObj);
            if(!grouped[dateKey]) grouped[dateKey] = [];
            g.parsedDate = dateObj;
            grouped[dateKey].push(g);
        });

        const sortedDates = Object.keys(grouped).sort((a, b) => grouped[a][0].parsedDate - grouped[b][0].parsedDate);
        let delay = 0;

        sortedDates.forEach(dateLabel => {
            grouped[dateLabel].sort((a, b) => a.parsedDate - b.parsedDate);
            const groupDiv = document.createElement('div');
            groupDiv.className = 'wc-date-group';
            groupDiv.style.animationDelay = `${delay}s`;
            delay += 0.1;
            
            const header = document.createElement('div');
            header.className = 'wc-date-header';
            header.innerHTML = `<span>${dateLabel}</span> <span style="font-size: 13px; color: var(--bd-text-muted); background: #fff; padding: 4px 10px; border-radius: 20px;">${grouped[dateLabel].length} Matches</span>`;
            groupDiv.appendChild(header);

            grouped[dateLabel].forEach(game => {
                const matchDiv = document.createElement('div');
                matchDiv.className = `wc-match`;

                const homeName = game.home_team_name_en || game.home_team_label || 'TBD';
                const awayName = game.away_team_name_en || game.away_team_label || 'TBD';

                let scoreHTML = '';
                let statusHTML = '';

                if (game.finished === 'TRUE') {
                    scoreHTML = `<div class="wc-score-box">${game.home_score} - ${game.away_score}</div>`;
                    statusHTML = `<span class="wc-match-status wc-status-ft">Full Time</span>`;
                } else if (game.time_elapsed !== 'notstarted' && game.time_elapsed !== 'finished') {
                    scoreHTML = `<div class="wc-score-box">${game.home_score} - ${game.away_score}</div>`;
                    statusHTML = `<span class="wc-match-status wc-status-live">Live: ${game.time_elapsed}</span>`;
                } else {
                    scoreHTML = `<div class="wc-score-box not-started">VS</div>`;
                    statusHTML = `<span class="wc-match-status">Upcoming</span>`;
                }

                let groupLabel = game.type === 'group' ? `Group ${game.group}` : game.type.toUpperCase();
                const homeScorers = parseScorers(game.home_scorers);
                const awayScorers = parseScorers(game.away_scorers);
                const hasScorers = homeScorers.length > 0 || awayScorers.length > 0;

                const matchMain = `
                    <div class="wc-match-main">
                        <div class="wc-match-time">
                            <span>${formatTime(game.parsedDate)}</span>
                            ${statusHTML}
                        </div>
                        <div class="wc-match-teams">
                            <div class="wc-team home"><span>${homeName}</span>${getFlagSpan(homeName, true)}</div>
                            ${scoreHTML}
                            <div class="wc-team away">${getFlagSpan(awayName, true)}<span>${awayName}</span></div>
                        </div>
                        <div class="wc-match-chevron">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </div>
                    </div>
                `;

                let detailsHTML = `<div class="wc-match-details"><div class="wc-details-inner"><div class="wc-meta-bar"><span><strong>Stage:</strong> ${groupLabel}</span><span><strong>Matchday:</strong> ${game.matchday}</span></div>`;
                if (hasScorers) {
                    detailsHTML += `<div class="wc-scorers"><div class="wc-scorers-home">${buildScorersHTML(homeScorers)}</div><div class="wc-scorers-away">${buildScorersHTML(awayScorers)}</div></div>`;
                } else {
                    detailsHTML += `<div style="text-align:center; color: var(--bd-text-muted); font-size: 13px;">No goals recorded yet.</div>`;
                }
                detailsHTML += `</div></div>`;

                matchDiv.innerHTML = matchMain + detailsHTML;
                matchDiv.addEventListener('click', () => { matchDiv.classList.toggle('expanded'); });
                groupDiv.appendChild(matchDiv);
            });
            contentDiv.appendChild(groupDiv);
        });
    }

    // --- Predictor Logic ---
    window.advancePredictorTeam = function(round, matchIndex, teamName) {
        if(!teamName) return;
        bracketState[round][matchIndex].winner = teamName;
        
        let nextRound;
        if (round === 'r32') nextRound = 'r16';
        else if (round === 'r16') nextRound = 'qf';
        else if (round === 'qf') nextRound = 'sf';
        else if (round === 'sf') nextRound = 'final';
        else if (round === 'final') {
            bracketState.champion = teamName;
            renderPredictor();
            return;
        }

        let nextIndex = Math.floor(matchIndex / 2);
        let isHomeSlot = matchIndex % 2 === 0;

        if (isHomeSlot) {
            bracketState[nextRound][nextIndex].home = teamName;
        } else {
            bracketState[nextRound][nextIndex].away = teamName;
        }
        
        // Clear cascading selections if they changed path
        clearPredictorCascade(nextRound, nextIndex);
        renderPredictor();
    }

    window.setR32Team = function(matchIndex, slot, teamName) {
        bracketState.r32[matchIndex][slot] = teamName;
        if (bracketState.r32[matchIndex].winner) {
            clearPredictorCascade('r32', matchIndex);
        }
        renderPredictor();
    }

    function clearPredictorCascade(round, matchIndex) {
        bracketState[round][matchIndex].winner = null;
        let nextRound;
        if (round === 'r32') nextRound = 'r16';
        else if (round === 'r16') nextRound = 'qf';
        else if (round === 'qf') nextRound = 'sf';
        else if (round === 'sf') nextRound = 'final';
        else {
            bracketState.champion = null;
            return;
        }

        let nextIndex = Math.floor(matchIndex / 2);
        let isHomeSlot = matchIndex % 2 === 0;

        if (isHomeSlot) bracketState[nextRound][nextIndex].home = '';
        else bracketState[nextRound][nextIndex].away = '';

        clearPredictorCascade(nextRound, nextIndex);
    }

    window.resetPredictor = function() {
        if(confirm('Are you sure you want to reset your entire bracket?')) {
            ['r16','qf','sf','final'].forEach(round => {
                bracketState[round].forEach(m => { m.home = ''; m.away = ''; m.winner = null; });
            });
            bracketState.r32.forEach(m => { m.winner = null; });
            bracketState.champion = null;
            renderPredictor();
        }
    }

    function renderPredictor() {
        const contentDiv = document.getElementById('wc-content');
        
        let html = `
            <div style="background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 5px; border-left: 4px solid var(--bd-red); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <div>
                    <h3 style="margin:0 0 10px; font-size: 18px;">Build your path to glory</h3>
                    <p style="margin:0; font-size: 18px; line-height: 2.0; color: var(--bd-text-muted);">
                        Select the 32 advancing teams using the dropdowns, then click a team to advance them through the bracket. 
                        <strong>Correctly predict the final four teams (Semi-Finalists) to win a special gift!</strong><br>
                        A <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:var(--bd-red);"></span> indicates your selected winner.
                    </p>
                </div>
                <button class="wc-reset-btn" onclick="resetPredictor()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                    Reset Bracket
                </button>
            </div>
            <div class="wc-bracket-wrapper">
                <div class="wc-bracket-container">
        `;

        const roundsData = [
            { key: 'r32', label: 'Round of 32', matches: bracketState.r32 },
            { key: 'r16', label: 'Round of 16', matches: bracketState.r16 },
            { key: 'qf', label: 'Quarter-Finals', matches: bracketState.qf },
            { key: 'sf', label: 'Semi-Finals', matches: bracketState.sf },
            { key: 'final', label: 'World Cup Final', matches: bracketState.final }
        ];

        roundsData.forEach(rd => {
            html += `<div class="wc-bracket-round">
                        <div class="wc-bracket-round-title">${rd.label}</div>`;
            
            rd.matches.forEach((match, idx) => {
                const homeEmpty = match.home ? '' : 'empty';
                const awayEmpty = match.away ? '' : 'empty';
                const homeName = match.home || 'TBD';
                const awayName = match.away || 'TBD';
                
                let homeWinner = match.winner === match.home && match.home !== '';
                let awayWinner = match.winner === match.away && match.away !== '';

                let homeKnocked = awayWinner ? 'knocked-out' : '';
                let awayKnocked = homeWinner ? 'knocked-out' : '';
                
                const homeClass = `wc-pteam ${homeEmpty} ${homeWinner ? 'winner' : ''} ${homeKnocked}`;
                const awayClass = `wc-pteam ${awayEmpty} ${awayWinner ? 'winner' : ''} ${awayKnocked}`;

                let htmlContent = '';
                if (rd.key === 'r32') {
                    const homeUI = match.apiHome ? 
                        `<span>${homeName}</span>` : 
                        `<select onclick="event.stopPropagation()" onchange="window.setR32Team(${idx}, 'home', this.value)" class="wc-pselect">${selectOptionsHTML.replace(`value="${match.home}"`, `value="${match.home}" selected`)}</select>`;
                    
                    const awayUI = match.apiAway ? 
                        `<span>${awayName}</span>` : 
                        `<select onclick="event.stopPropagation()" onchange="window.setR32Team(${idx}, 'away', this.value)" class="wc-pselect">${selectOptionsHTML.replace(`value="${match.away}"`, `value="${match.away}" selected`)}</select>`;

                    htmlContent = `
                        <div class="${homeClass}" onclick="advancePredictorTeam('${rd.key}', ${idx}, '${match.home}')">
                            ${getFlagSpan(match.home)} ${homeUI}
                        </div>
                        <div class="${awayClass}" onclick="advancePredictorTeam('${rd.key}', ${idx}, '${match.away}')">
                            ${getFlagSpan(match.away)} ${awayUI}
                        </div>
                    `;
                } else {
                    htmlContent = `
                        <div class="${homeClass}" onclick="advancePredictorTeam('${rd.key}', ${idx}, '${match.home}')">
                            ${getFlagSpan(match.home)} <span>${homeName}</span>
                        </div>
                        <div class="${awayClass}" onclick="advancePredictorTeam('${rd.key}', ${idx}, '${match.away}')">
                            ${getFlagSpan(match.away)} <span>${awayName}</span>
                        </div>
                    `;
                }

                html += `
                    <div class="wc-pmatch">
                        ${htmlContent}
                    </div>
                `;
            });
            html += `</div>`;
        });

        // Champion Column
        html += `<div class="wc-bracket-round" style="justify-content: center;">`;
        if (bracketState.champion) {
            html += `
                <div class="wc-pmatch-champ">
                    <h3>2026 Champion</h3>
                    <div class="champion-name">
                        ${getFlagSpan(bracketState.champion)} ${bracketState.champion}
                    </div>
                </div>
            `;
        } else {
            html += `
                <div class="wc-pmatch-champ" style="background: var(--bd-light-gray); color: var(--bd-text-muted); box-shadow: none;">
                    <h3>Champion</h3>
                    <div class="champion-name">?</div>
                </div>
            `;
        }

        const sfTeams = bracketState.sf.flatMap(m => [m.home, m.away]).filter(t => t);
        const isComplete = bracketState.champion !== null;
        
        html += `
                <div class="wc-submission-box" style="${isComplete ? '' : 'opacity: 0.6; pointer-events: none;'}">
                    <h4>Win a Special Gift!</h4>
                    <p style="font-size: 13px; color: var(--bd-text-muted); margin-bottom: 15px;">
                        ${isComplete ? `You have predicted the final four: <br><strong>${sfTeams.join(', ')}</strong>` : '<em>Complete your entire bracket to unlock submission!</em>'}
                    </p>
                    <form onsubmit="submitPredictionForm(event)">
                        <input type="text" id="pred_name" placeholder="Your Full Name" required ${isComplete ? '' : 'disabled'}>
                        <input type="email" id="pred_email" placeholder="Your Email Address" required ${isComplete ? '' : 'disabled'}>
                        <input type="tel" id="pred_phone" placeholder="Your Phone Number" required ${isComplete ? '' : 'disabled'}>
                        <button type="submit" id="pred_submit_btn" ${isComplete ? '' : 'disabled'} style="${isComplete ? '' : 'background: var(--bd-gray);'}">Submit Prediction</button>
                        <div id="pred_msg"></div>
                    </form>
                </div>
        `;

        html += `</div></div></div>`;
        
        contentDiv.innerHTML = html;
    }

    window.submitPredictionForm = function(e) {
        e.preventDefault();
        const btn = document.getElementById('pred_submit_btn');
        const msg = document.getElementById('pred_msg');
        btn.disabled = true;
        btn.innerText = "Submitting...";

        const sfTeams = bracketState.sf.flatMap(m => [m.home, m.away]).filter(t => t);
        
        const formData = new URLSearchParams();
        formData.append('action', 'wc_submit_prediction');
        formData.append('pred_name', document.getElementById('pred_name').value);
        formData.append('pred_email', document.getElementById('pred_email').value);
        formData.append('pred_phone', document.getElementById('pred_phone').value);
        formData.append('sf1', sfTeams[0] || '');
        formData.append('sf2', sfTeams[1] || '');
        formData.append('sf3', sfTeams[2] || '');
        formData.append('sf4', sfTeams[3] || '');

        fetch(window.location.origin + '/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: formData,
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                msg.className = 'success';
                msg.innerText = "Thank you! Your prediction has been saved.";
                btn.style.display = 'none';
            } else {
                msg.className = 'error';
                msg.innerText = data.data || "An error occurred.";
                btn.disabled = false;
                btn.innerText = "Submit Prediction";
            }
        }).catch(err => {
            msg.className = 'error';
            msg.innerText = "Connection error.";
            btn.disabled = false;
            btn.innerText = "Submit Prediction";
        });
    }

    // --- Init & Caching ---
    const CACHE_KEY = 'wc_api_data';
    const CACHE_TIME = 5 * 60 * 1000; // 5 minutes

    function loadAPIData() {
        const cached = localStorage.getItem(CACHE_KEY);
        if (cached) {
            try {
                const parsed = JSON.parse(cached);
                if (Date.now() - parsed.timestamp < CACHE_TIME) {
                    processAPIData(parsed.data);
                    return;
                }
            } catch(e) {}
        }

        fetch(API_URL)
            .then(res => res.json())
            .then(data => {
                if(data && data.games) {
                    localStorage.setItem(CACHE_KEY, JSON.stringify({
                        timestamp: Date.now(),
                        data: data
                    }));
                    processAPIData(data);
                } else {
                    throw new Error("Invalid data format");
                }
            })
            .catch(err => {
                // If API fails, try to fall back to older cached data if it exists
                if (cached) {
                    try {
                        const parsed = JSON.parse(cached);
                        processAPIData(parsed.data);
                        return;
                    } catch(e) {}
                }
                document.getElementById('wc-content').innerHTML = `
                    <div class="wc-loading" style="color: var(--bd-red);">
                        Unable to load match data. Please try again later.<br>
                        <small>${err.message}</small>
                    </div>
                `;
            });
    }

    function processAPIData(data) {
        allGames = data.games;
        extractTeamsFromAPI();
        initBracketFromAPI();
        populateGroupFilters();
        renderGames();
    }

    loadAPIData();

    // Tab Events
    const tabs = document.querySelectorAll('.wc-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', (e) => {
            tabs.forEach(t => t.classList.remove('active'));
            e.target.classList.add('active');
            currentTab = e.target.getAttribute('data-filter');
            currentGroupFilter = 'all';
            populateGroupFilters();
            renderGames();
        });
    });
});
</script>

<?php get_footer(); ?>

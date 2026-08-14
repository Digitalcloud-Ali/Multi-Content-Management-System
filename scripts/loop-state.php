<?php
/**
 * MultiCMS readiness loop state — updated by the agent loop.
 */
return [
    'goal' => 'Demo-ready MultiCMS on master: install, core posts, pack apply, security basics, smoke green',
    'completed_phases' => [1, 2, 3, 4],
    'current_phase' => 5,
    'phase4' => [
        'safe_description_escape' => 'done',
        'blog_sample_seed' => 'done',
        'release_checklist' => 'done',
        'smoke_green' => 'done',
        'pack_theme_dual_header' => 'done',
        'rayicecms_compat' => 'done',
    ],
    'phase5_next' => [
        'ci_mysql_smoke' => 'pending',
        'github_actions_service_container' => 'pending',
    ],
    'stop_when' => [
        'smoke-phase2.php ALL PASS',
        'php-lint-smoke 0 failures',
        'Phase 4 items complete and pushed to master',
        'CI runs smoke against MySQL service (Phase 5)',
    ],
    'last_smoke' => 'ALL PASS',
    'updated_at' => date('c'),
];

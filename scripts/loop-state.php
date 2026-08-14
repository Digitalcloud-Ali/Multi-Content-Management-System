<?php
return [
    'goal' => 'Production-ready Soft launch / Fresh core',
    'completed_phases' => [1, 2, 3, 4, 5, 6],
    'current_phase' => 6,
    'phase6_publish_gate' => [
        'uploads' => 'done',
        'dashboard' => 'done',
        'raycms_scrub' => 'done',
        'env_production' => 'done',
        'install_403' => 'done',
        'admin_csrf' => 'done',
        'local_smoke' => 'ALL PASS',
    ],
    'next_prompt' => 'Verify GitHub Actions green for Phase 6 push. If red, fix and push. If green, update DEMO_READY and stop loop — Fresh core production gate met (packs remain demo-only).',
    'updated_at' => date('c'),
];

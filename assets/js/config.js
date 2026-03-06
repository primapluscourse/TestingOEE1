// assets/js/config.js - File ini akan digenerate oleh PHP
window.__CONFIG__ = {
    SUPABASE_URL: '<?php echo $config['supabase']['url']; ?>',
    SUPABASE_ANON_KEY: '<?php echo $config['supabase']['key']; ?>',
    MANAGEMENT_PASSWORD: '<?php echo $config['management']['password']; ?>'
};

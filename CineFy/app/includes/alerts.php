<?php $note = get_note(); ?>
<?php if ($note): ?>
    <div class="container">
        <div class="alert-box alert-<?php echo htmlspecialchars($note['type']); ?>">
            <p><?php echo htmlspecialchars($note['message']); ?></p>
        </div>
    </div>
<?php endif; ?>
<?php if (!db_available()): ?>
    <div class="container">
        <div class="alert-box alert-note">
            <p>Database connection is not active yet. The website is using fallback movie data until a dev imports the SQL files.</p>
        </div>
    </div>
<?php endif; ?>

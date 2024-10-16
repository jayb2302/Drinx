<div class="form-container">
    <form action="/profile/update" method="post" enctype="multipart/form-data">
        <!-- First Name -->
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($profile->getFirstName() ?? '') ?>" required>
        </div>

        <!-- Last Name -->
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($profile->getLastName() ?? '') ?>" required>
        </div>

        <!-- Bio -->
        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea name="bio" id="bio"><?= htmlspecialchars($profile->getBio() ?? '') ?></textarea>
        </div>
        <!-- Profile Picture Upload -->
         <div class="form-group">

             <label for="profile_picture">Profile Picture</label>
             <input type="file" name="profile_picture" id="profile_picture">
             <?php if ($profile->getProfilePicture()): ?>
                <img src="<?= asset('uploads/profile_pictures/' . htmlspecialchars($profile->getProfilePicture())); ?>" alt="Profile Picture" width="100">
                <?php endif; ?>
            </div>

        <button type="submit" class="btn btn-success">Save Changes</button>
    </form>
</div>
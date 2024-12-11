<div class="form-container">

    <form action="/profile/update" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generateCsrfToken()); ?>" />
         <!-- Profile Picture Upload -->
         <div class="form-group">
            <label for="profile_picture">Profile Picture</label>
            <input type="file" name="profile_picture" id="profile_picture">
            <span id="file-error" style="color: red; display: none;"></span>
            <img id="image-preview" src="<?= $profile->getProfilePicture() ? asset('/../uploads/users/' . htmlspecialchars($profile->getProfilePicture())) : ''; ?>"
                 alt="Profile Picture Preview"
                 style="display: <?= $profile->getProfilePicture() ? 'block' : 'none'; ?>; width: 100px;">
        </div>
        <!-- First Name -->
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" name="first_name" id="first_name"
                value="<?= htmlspecialchars($profile->getFirstName() ?? '') ?>" required>
        </div>

        <!-- Last Name -->
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" name="last_name" id="last_name"
                value="<?= htmlspecialchars($profile->getLastName() ?? '') ?>" required>
        </div>

        <!-- Bio -->
        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea name="bio" id="bio"><?= htmlspecialchars($profile->getBio() ?? '') ?></textarea>
        </div>
        <!-- Social Media Platform Dropdown -->
        <div class="form-group">
            <label for="platform-select">Social Media Platform</label>
            <?php
            $defaultSelectedPlatform = ''; // Holds the first platform with a URL to set as default
            foreach ($formData as $platform) {
                if (!empty($platform['url']) && empty($defaultSelectedPlatform)) {
                    $defaultSelectedPlatform = $platform['platform_id']; // Set first platform with a URL as default
                }
            }
            ?>
            <select id="platform-select" class="form-control">
                <option value="">Select a platform</option>
                <?php foreach ($formData as $platform): ?>
                    <option value="<?= $platform['platform_id']; ?>" <?= $platform['platform_id'] == $defaultSelectedPlatform ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($platform['platform_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <!-- Social Media Link Input Fields -->
        <div id="social-link-container">
            <?php foreach ($formData as $platform): ?>
                <div class="social-link-group" id="social-input-<?= $platform['platform_id']; ?>"
                    style="display: <?= $platform['platform_id'] == $defaultSelectedPlatform ? 'block' : 'none'; ?>;">
                    <div class="form-group">
                        <input type="url" name="social_links[<?= $platform['platform_id']; ?>]"
                            id="input_<?= $platform['platform_id']; ?>" class="form-control social-input"
                            value="<?= htmlspecialchars($platform['url']); ?>"
                            placeholder="https://<?= htmlspecialchars($platform['platform_name']); ?>.com/profile">

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="btn btn-success">Save Changes</button>
    </form>
    <button type="button" class="close-button" id="close-form-button">
    <i class="fa-solid fa-xmark"></i>
</button>
</div>
<div class="admin-container">
    <h1 class="admin-control-title">Manage Users</h1>
    <input type="text" id="userSearch" placeholder="Search users..." class="user-search-input">
    <div class="user-container">
        <table class="manage-users">
            <thead>
                <tr>
                    <th></th>
                    <th data-sort="username" class="sortable">Username <span class="sort-indicator"></span></th>
                    <th data-sort="email" class="sortable">Email <span class="sort-indicator"></span></th>
                    <th data-sort="status" class="sortable">Account Status <span class="sort-indicator"></span></th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <?php foreach ($users as $user): ?>
                    <?php if (!$user->isAdmin()): ?> <!-- Exclude admin users -->
                        <tr class="users-rows">
                            <td>
                                <a href="/profile/<?= htmlspecialchars($user->getUsername()); ?>" class="view-profile">
                                    <img class="profile-pictue m"
                                        alt="Profile picture of <?= htmlspecialchars($user->getUsername()); ?>">
                                </a>
                            </td>
                            <td><?= htmlspecialchars($user->getUsername() ?? '') ?></td>
                            <td><?= htmlspecialchars($user->getEmail() ?? '') ?></td>
                            <td><?= htmlspecialchars($user->getAccountStatusName() ?? '') ?></td>

                            <td>
                                <form class="update-status-form" data-user-id="<?= $user->getId(); ?>">
                                    <input type="hidden" name="user_id" value="<?= $user->getId(); ?>">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                                    <select name="status_id">
                                        <option value="1" <?= $user->getAccountStatusId() == 1 ? 'selected' : '' ?>>🟢</option>
                                        <option value="2" <?= $user->getAccountStatusId() == 2 ? 'selected' : '' ?>>🟡</option>
                                        <option value="3" <?= $user->getAccountStatusId() == 3 ? 'selected' : '' ?>>🔴</option>
                                    </select>
                                    <button class="button" type="submit">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
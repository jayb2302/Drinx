<h2>Manage Users</h2>
<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Account Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user->getUsername()); ?></td>
                <td><?= htmlspecialchars($user->getEmail()); ?></td>
                <td><?= htmlspecialchars($user->getAccountStatusName()); ?></td>
                <td>
                    <form class="update-status-form" data-user-id="<?= $user->getId(); ?>">
                        <input type="hidden" name="user_id" value="<?= $user->getId(); ?>">
                        <select name="status_id">
                            <option value="1" <?= $user->getAccountStatusId() == 1 ? 'selected' : '' ?>>Active</option>
                            <option value="2" <?= $user->getAccountStatusId() == 2 ? 'selected' : '' ?>>Suspended</option>
                            <option value="3" <?= $user->getAccountStatusId() == 3 ? 'selected' : '' ?>>Banned</option>
                        </select>
                        <button type="submit">Update Status</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
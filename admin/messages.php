<?php 
require 'includes/auth.php';
include 'includes/header.php';

if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM contacts WHERE id=" . $_GET['delete']);
    header('Location: messages.php');
    exit;
}
?>

<h1>Contact Messages</h1>

<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Message</th>
        <th>Date</th>
        <th>Action</th>
    </tr>
    <?php
    $messages = $conn->query("SELECT * FROM contacts ORDER BY created_at DESC");
    while ($msg = $messages->fetch_assoc()):
    ?>
    <tr>
        <td><?php echo $msg['name']; ?></td>
        <td><?php echo $msg['email']; ?></td>
        <td><?php echo substr($msg['message'], 0, 50); ?>...</td>
        <td><?php echo date('d M Y', strtotime($msg['created_at'])); ?></td>
        <td>
            <a href="?delete=<?php echo $msg['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php include 'includes/footer.php'; ?>

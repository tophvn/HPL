<style>
        .notification-wrapper {
            position: fixed; 
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: none;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .close {
            float: right;
            font-size: 1.2em;
            cursor: pointer;
        }
    </style>
<div class="notification-wrapper">
    <?php
    // Kiểm tra và hiển thị thông báo từ session
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $message_type = $_SESSION['message_type'];
        // Hiển thị thông báo tương ứng với loại thông báo
        echo '<div class="alert alert-' . $message_type . '" id="notification">';
        echo '<span class="close" onclick="this.parentElement.style.display=\'none\';">&times;</span>';
        echo '<strong>' . ucfirst($message_type) . '!</strong> ' . $message;
        echo '</div>';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
    ?>
</div>

<script>
    const notification = document.getElementById('notification');
    if (notification) {
        notification.style.display = 'block';
        setTimeout(() => {
            notification.style.display = 'none';
        }, 5000);
    }
</script>
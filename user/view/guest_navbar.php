<style>
    .navbar {
        background-color: var(--white);
        padding: 1rem 2rem;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .nav-brand {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--primary);
        text-decoration: none;
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .nav-link {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .nav-link:hover {
        color: var(--accent);
        transform: translateY(-2px);
    }

    .nav-button {
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .login-btn {
        background-color: var(--white);
        color: var(--primary);
        border: 2px solid var(--primary);
    }

    .register-btn {
        background-color: var(--primary);
        color: var(--white);
    }

    .nav-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .login-btn:hover {
        background-color: var(--primary);
        color: var(--white);
    }

    .register-btn:hover {
        background-color: var(--accent);
    }

    .nav-separator {
        height: 24px;
        width: 1px;
        background-color: #ddd;
        margin: 0 0.5rem;
    }

    @media (max-width: 768px) {
        .navbar {
            padding: 1rem;
        }

        .nav-links {
            gap: 1rem;
        }

        .nav-link span {
            display: none;
        }

        .nav-button {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    }
</style>

<nav class="navbar">
    <a href="index.php" class="nav-brand">Paradise Resort</a>
    <div class="nav-links">
        <a href="../admin/booking/rooms.php" class="nav-link">
            <i class="fas fa-bed"></i>
            <span>Rooms</span>
        </a>
        <a href="#" class="nav-link">
            <i class="fas fa-comment"></i>
            <span>Feedback</span>
        </a>
        <div class="nav-separator"></div>
        <a href="../login.php" class="nav-button login-btn">
            <i class="fas fa-sign-in-alt"></i> Login
        </a>
        <a href="../admin/customer/create.php" class="nav-button register-btn">
            <i class="fas fa-user-plus"></i> Register
        </a>
    </div>
</nav>
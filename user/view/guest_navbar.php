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
        gap: 1rem;
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

    @media (max-width: 768px) {
        .navbar {
            padding: 1rem;
        }
    }
</style>

<nav class="navbar">
    <a href="index.php" class="nav-brand">Paradise Resort</a>
    <div class="nav-links">
        <a href="../login.php" class="nav-button login-btn">Login</a>
        <a href="../admin/customer/create.php" class="nav-button register-btn">Register</a>
    </div>
</nav>
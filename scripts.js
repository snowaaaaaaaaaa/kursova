// тут функція шоб попапчики відкривались і закривались
function togglePopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.style.display = popup.style.display === 'none' ? 'block' : 'none';
    } else {
        console.error(`Попап з ID "${popupId}" не знайдено.`);
    }
}

// тут оброблаємо вхід
async function handleLogin(event) {
    event.preventDefault();
    const email = document.getElementById('login-email').value;
    const password = document.getElementById('login-password').value;

    try {
        const response = await fetch('login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ email, password })
        });

        if (!response.ok) throw new Error(`HTTP помилка: ${response.status}`);
        const result = await response.json();

        if (result.success) {
            showSuccessMessage('Вхід успішний!');
            togglePopup('loginPopup'); 
        } else {
            alert(result.message || 'Неправильний email або пароль');
        }
    } catch (error) {
        console.error("Помилка під час входу:", error);
        alert("Сталася помилка під час входу. Спробуйте ще раз.");
    }
}

// тут обробляємо реєстрацію
async function handleRegister(event) {
    event.preventDefault();
    const name = document.getElementById('register-name').value;
    const email = document.getElementById('register-email').value;
    const password = document.getElementById('register-password').value;

    try {
        const response = await fetch('register.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ name, email, password })
        });

        if (!response.ok) throw new Error(`HTTP помилка: ${response.status}`);
        const result = await response.json();

        if (result.success) {
            showSuccessMessage(`Реєстрація успішна! Вітаємо, ${name}!`);
            togglePopup('registerPopup'); 
        } else {
            alert(result.message || 'Помилка під час реєстрації');
        }
    } catch (error) {
        console.error("Помилка під час реєстрації:", error);
        alert("Сталася помилка під час реєстрації. Спробуйте ще раз.");
    }
}

// тут функція про успішне закінчення дії
function showSuccessMessage(message) {
    const successPopup = document.getElementById('successPopup');
    const successMessage = document.getElementById('successMessage');
    successMessage.textContent = message;
    successPopup.style.display = 'block';

    setTimeout(() => {
        successPopup.style.display = 'none';
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('.btn_svz');
            const messageDiv = document.getElementById('formMessage');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Отправка...';
            submitBtn.disabled = true;
            messageDiv.style.display = 'none';
            messageDiv.className = '';
            
            fetch('process_form.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Ответ сервера:', data);
                if (data.success) {
                    messageDiv.style.display = 'block';
                    messageDiv.className = 'success';
                    messageDiv.innerHTML = `<i class="fas fa-check-circle"></i> ${data.message}`;
                    form.reset();
                    
                    setTimeout(() => {
                        messageDiv.style.display = 'none';
                    }, 5000);
                } else {
                    messageDiv.style.display = 'block';
                    messageDiv.className = 'error';
                    
                    let errorMessage = `<i class="fas fa-exclamation-triangle"></i> ${data.message}`;
                    if (data.errors) {
                        errorMessage += '<ul>';
                        data.errors.forEach(error => {
                            errorMessage += `<li>${error}</li>`;
                        });
                        errorMessage += '</ul>';
                    }
                    messageDiv.innerHTML = errorMessage;
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                messageDiv.style.display = 'block';
                messageDiv.className = 'error';
                messageDiv.innerHTML = `<i class="fas fa-times-circle"></i> Произошла ошибка при отправке формы. Проверьте консоль для подробностей.`;
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Плавная прокрутка для навигации
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Анимация при скролле
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.workers_container, .container_sub, .img_container1, .img_container2, .img_container3, .img_container4, .num1, .num2, .num3, .num4').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
    
    // Обработчик для кнопки "обсудить проект"
    const projectBtn = document.querySelector('.btn_proj');
    if (projectBtn) {
        projectBtn.addEventListener('click', function() {
            document.querySelector('#contact').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    }
});
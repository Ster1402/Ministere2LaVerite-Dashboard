document.addEventListener('DOMContentLoaded', function() {
    const loginTab = document.getElementById('login-tab');
    const signupTab = document.getElementById('signup-tab');
    const loginSection = document.getElementById('login-section');
    const signupSection = document.getElementById('signup-section');

    if (loginTab && signupTab) {
        loginTab.addEventListener('click', (e) => {
            e.preventDefault();
            loginTab.classList.add('active');
            signupTab.classList.remove('active');
            loginSection.classList.add('active');
            signupSection.classList.remove('active');
        });

        signupTab.addEventListener('click', (e) => {
            e.preventDefault();
            signupTab.classList.add('active');
            loginTab.classList.remove('active');
            signupSection.classList.add('active');
            loginSection.classList.remove('active');
        });
    }

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Registration stepper
    const form = document.getElementById('signup-form');
    if (form) {
        const steps = document.querySelectorAll('.step');
        const contents = document.querySelectorAll('.step-content');
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const submitBtn = document.getElementById('submitBtn');
        let currentStep = 1;
        const totalSteps = steps.length;

        function updateSteps() {
            steps.forEach((step, index) => {
                const stepNum = index + 1;
                if (stepNum < currentStep) {
                    step.classList.add('completed');
                    step.classList.remove('active');
                } else if (stepNum === currentStep) {
                    step.classList.add('active');
                    step.classList.remove('completed');
                } else {
                    step.classList.remove('active', 'completed');
                }
            });

            contents.forEach((content, index) => {
                const contentNum = index + 1;
                if (contentNum === currentStep) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });

            updateButtons();
        }

        function updateButtons() {
            prevBtn.style.display = currentStep === 1 ? 'none' : 'block';
            nextBtn.style.display = currentStep === totalSteps ? 'none' : 'block';
            submitBtn.style.display = currentStep === totalSteps ? 'block' : 'none';
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateSteps();
                }
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    updateSteps();
                }
            });
        }

        // Initialize if elements exist
        if (steps.length > 0) {
            updateSteps();
        }
    }
});

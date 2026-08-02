function showToast(message, type = 'info') {
	const toast = document.getElementById('toast');
	if (!toast) return;

	toast.textContent = message;
	toast.classList.remove('hidden');
	toast.style.opacity = '1';
	toast.style.transform = 'translateY(0)';

	if (type === 'success') {
		toast.style.backgroundColor = '#065f46';
	} else if (type === 'error') {
		toast.style.backgroundColor = '#831843';
	} else {
		toast.style.backgroundColor = '#0f172a';
	}

	// auto hide
	setTimeout(() => {
		toast.style.opacity = '0';
		toast.style.transform = 'translateY(8px)';
		setTimeout(() => toast.classList.add('hidden'), 400);
	}, 3500);
}

document.addEventListener('DOMContentLoaded', () => {
	// expose for inline scripts
	window.showToast = showToast;
});

export {};

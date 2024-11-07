const timeout = 5000;
const messages = document.querySelectorAll('.messages>div')
const removeMessage = (elem) => {
	const parent = elem.parentNode;
	if (parent === null) {
		return;
	}
	parent.removeChild(elem);
	if (parent.children.length === 0) {
		parent.remove();
	}
};
for (let i = 0; i < messages.length; i++) {
	const message = messages[i];
	message.querySelector('img').addEventListener('click', () => removeMessage(message));
	setTimeout(() => removeMessage(message), timeout + i * 500);
}
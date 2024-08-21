const randomMessages = [
    "If you delete your product, it cannot be restored."
];

function showRandomNotification() {

    const randomIndex = Math.floor(Math.random() * randomMessages.length);

    Swal.fire({
        icon: 'info',
        title: '<span style="font-size: 18px;">Just a quick reminder</span>',
        html: `<span style="font-size: 13px;">${randomMessages[randomIndex]}</span>`,
    });
}

document.addEventListener('DOMContentLoaded', showRandomNotification);

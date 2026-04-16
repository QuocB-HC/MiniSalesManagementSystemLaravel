const confirmModal = (event, title, message, type = "confirm") => {
    // Block form submission immediately
    event.preventDefault();

    // Save the reference of the currently clicked form
    const element = event.currentTarget;

    // Create a default configuration object (For confirmation)
    let config = {
        title: title,
        text: message,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Confirm",
        cancelButtonText: "Cancel",
        reverseButtons: true,
    };

    // If delete type
    if (type === "delete") {
        config.icon = "warning";
        config.confirmButtonText = "Delete";
    }

    Swal.fire(config).then((result) => {
        if (result.isConfirmed) {
            if (element.tagName === "FORM") {
                element.submit();
            } else if (element.tagName === "A") {
                window.location.href = element.href;
            }
        }
    });
};

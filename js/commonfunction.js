function showMessageBoxPermission(content) {
    const messageBox = document.getElementById('messageBox-alert');

    // Populate the messageBox with the content and appropriate styling
    messageBox.innerHTML = `
    <div style="width:50%; padding: 5px; height:30px;border-radius: 2px; float:right; " class="alert alert-warning alert-block square fade in alert-dismissable">
    <button style="width: 45px;" type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <p style="width:90%;">${content}</p>
    </div>`;

    // Show the message box
    messageBox.style.display = "block";

    // Automatically hide the message box after 4 seconds
    setTimeout(() => {
        messageBox.style.display = "none";
    }, 4000);
}
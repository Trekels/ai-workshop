(() => {
  const chatBox = document.querySelector('.msger-chat');
  const messageInput = document.querySelector('.msger-input');

  const formatDate = date => {
    const h = "0" + date.getHours();
    const m = "0" + date.getMinutes();

    return `${h.slice(-2)}:${m.slice(-2)}`;
  }

  const appendUserMessage = text => {
    chatBox.insertAdjacentHTML('beforeend', `
      <div class="msg right-msg">
        <div class="msg-img" style="background-image: url(https://icons.veryicon.com/png/o/file-type/linear-icon-2/user-132.png)"></div>
  
        <div class="msg-bubble">
          <div class="msg-info">
            <div class="msg-info-name">Explorer</div>
            <div class="msg-info-time">${formatDate(new Date())}</div>
          </div>
  
          <div class="msg-text">${text}</div>
        </div>
      </div>
    `);

    chatBox.scrollTop += 500;
  }

  const handleMessage = form => {
    fetch(form.action, { method: 'POST', body: JSON.stringify({ message: messageInput.value }) })
      .then(r => r.text().then(content => chatBox.insertAdjacentHTML('beforeend', content)));
  }

  document.querySelector('form').addEventListener('submit', event => {
    event.preventDefault();

    const msgText = messageInput.value;
    if (!msgText) return;

    appendUserMessage(msgText);
    handleMessage(event.target);

    messageInput.value = "";
  });
})()

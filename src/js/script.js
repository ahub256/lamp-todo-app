document.addEventListener('DOMContentloaded',function () {
  const forms = document.querySelectorAll('form');

  forms.forEach(form => {
    form.addEventListener('submit',function (event) {
      const requiredInputs = form.querySelectorAll('input[required]');
      let isValid = true;

      requiredInputs.forEach(input => {
        if (!input.value.trim()) {
          isValid = false;
          input.classList.add('is-invalid');
        } else {
          input.classList.remove('is-invalid');
        }
      });

      if (!isValid) {
        event.preventDefault();
        alert('必須項目を入力してください');
      }
    });
  });

  const deleteButtons = document.querySelectorAll('.btn-danger[onclick]');

  deleteButtons.forEach(button => {
    button.addEventListener('click',function(event) {
      if (!confirm('本当に削除しますか？')) {
        event.preventDefault();
      }
    });
  });
  
});

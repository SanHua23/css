<?php
session_start();


if (isset($_SESSION['user_id'])) {
	header('location: admin/f2f-schedule');
}

?>

<?php include 'plugins-header.php';?>
<style type="text/css">
  form {
    padding: 2rem;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,.1);
    max-width: 500px;
    background: #fff;
    
    .form-control {
      display: block;
      height: 50px;
      margin-right: 0.5rem;
      text-align: center;
      font-size: 1.25rem;
      min-width: 0;
      
      &:last-child {
        margin-right: 0;
      }
    }
  }
</style>
   
<div class="row mx-0 d-flex justify-content-center align-items-center" style="background-color: #fff; min-height: 100vh;">
   <div class="col-10 col-sm-8 col-xl-7 col-xxl-5  row mx-0 shadow-lg bg-white rounded flex-column-reverse flex-lg-row p-4">
        
        <form id="verification">
            <h4 class="text-center mb-4">Enter your code</h4>
            <p class="text-center">We've sent you a verification code. Please check your email.</p>
        
            <div class="d-flex mb-3">
              <input type="tel" maxlength="1" pattern="[0-9]" class="form-control" name="status[]">
              <input type="tel" maxlength="1" pattern="[0-9]" class="form-control" name="status[]">
              <input type="tel" maxlength="1" pattern="[0-9]" class="form-control" name="status[]">
              <input type="tel" maxlength="1" pattern="[0-9]" class="form-control" name="status[]">
              <input type="tel" maxlength="1" pattern="[0-9]" class="form-control" name="status[]">
              <input type="tel" maxlength="1" pattern="[0-9]" class="form-control" name="status[]">
            </div>
            <button type="submit" class="w-100 btn btn-primary">Verify account</button>
      </form>
   </div>
</div>

<?php include 'plugins-footer.php' ?>
<script type="text/javascript">
  const form = document.querySelector('form')
  const inputs = form.querySelectorAll('input')
  const KEYBOARDS = {
    backspace: 8,
    arrowLeft: 37,
    arrowRight: 39,
  }

  function handleInput(e) {
    const input = e.target
    const nextInput = input.nextElementSibling
    if (nextInput && input.value) {
      nextInput.focus()
      if (nextInput.value) {
        nextInput.select()
      }
    }
  }

  function handlePaste(e) {
    e.preventDefault()
    const paste = e.clipboardData.getData('text')
    inputs.forEach((input, i) => {
      input.value = paste[i] || ''
    })
  }

  function handleBackspace(e) { 
    const input = e.target
    if (input.value) {
      input.value = ''
      return
    }
    
    input.previousElementSibling.focus()
  }

  function handleArrowLeft(e) {
    const previousInput = e.target.previousElementSibling
    if (!previousInput) return
    previousInput.focus()
  }

  function handleArrowRight(e) {
    const nextInput = e.target.nextElementSibling
    if (!nextInput) return
    nextInput.focus()
  }

  form.addEventListener('input', handleInput)
  inputs[0].addEventListener('paste', handlePaste)

  inputs.forEach(input => {
    input.addEventListener('focus', e => {
      setTimeout(() => {
        e.target.select()
      }, 0)
    })
    
    input.addEventListener('keydown', e => {
      switch(e.keyCode) {
        case KEYBOARDS.backspace:
          handleBackspace(e)
          break
        case KEYBOARDS.arrowLeft:
          handleArrowLeft(e)
          break
        case KEYBOARDS.arrowRight:
          handleArrowRight(e)
          break
        default:  
      }
    })
  })

</script>
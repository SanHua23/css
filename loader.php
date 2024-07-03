
<style>
  .loader-container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    background: rgba(0, 0, 0, 0.5);
  }

  .loader {
    width: 70px;
    height: 70px;
    position: relative;
  }

  .loader:before {
    content: "";
    width: 70px;
    height: 70px;
    border-radius: 50%;
    border: 6px solid #007bff;
    position: absolute;
    top: 0;
    left: 0;
    animation: pulse 1s ease-in-out infinite;
  }

  .loader:after {
    content: "";
    width: 70px;
    height: 70px;
    border-radius: 50%;
    border: 6px solid transparent;
    border-top-color: #007bff;
    position: absolute;
    top: 0;
    left: 0;
    animation: spin 2s linear infinite;
  }

  @keyframes pulse {
    0% {
      transform: scale(0.6);
      opacity: 1;
    }
    50% {
      transform: scale(1.2);
      opacity: 0;
    }
    100% {
      transform: scale(0.6);
      opacity: 1;
    }
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }


  .loaded .loader-container {
    display: none;
  }

  .loaded .content {
    display: block;
  }
</style>
<div class="loader-container d-none" >
  <div class="loader"></div>
</div>
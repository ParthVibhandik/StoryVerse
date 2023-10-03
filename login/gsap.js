var loginBtn = document.getElementById("login-open");
var signupBtn = document.getElementById("signup-open");
var loginReBtn = document.getElementById("login-repen");
var container = document.getElementById("signup-login-container");
container.style.backgroundImage = "url('Assets/candleoff.jpg')";

const open = gsap.timeline();
open
  .to("#scroll-paper", {
    duration: 1,
    height: 283,
    delay: 0.5,
  })
  .to(".login", {
    duration: 1,
    display: "flex",
    opacity: 1,
  })
  .to(
    container,
    {
      duration: 1,
      backgroundImage: "url('Assets/candleon.jpg')",
    },
    "-=1"
  );

signupBtn.addEventListener("click", function () {
  const reopen = gsap.timeline();
  reopen
    .to(container, {
      duration: 1,
      backgroundImage: "url('Assets/candleoff.jpg')",
    })
    .to(
      ".login",
      {
        duration: 1,
        display: "none",
        opacity: 0,
      },
      "-=1"
    )
    .to(
      "#scroll-paper",
      {
        duration: 1,
        height: 18,
      },
      "-=.25"
    )
    .to("#scroll-paper", {
      duration: 1,
      height: 283,
      delay: 0.25,
    })
    .to(".signup", {
      duration: 1,
      display: "flex",
      opacity: 1,
    })
    .to(
      container,
      {
        duration: 1,
        backgroundImage: "url('Assets/candleon.jpg')",
      },
      "-=1"
    );
});

loginReBtn.addEventListener("click", function () {
  const reopen = gsap.timeline();
  reopen
    .to(container, {
      duration: 1,
      backgroundImage: "url('Assets/candleoff.jpg')",
    })
    .to(
      ".signup",
      {
        duration: 1,
        display: "none",
        opacity: 0,
      },
      "-=1"
    )
    .to(
      "#scroll-paper",
      {
        duration: 1,
        height: 18,
      },
      "-=.25"
    )
    .to("#scroll-paper", {
      duration: 1,
      height: 283,
      delay: 0.25,
    })
    .to(".login", {
      duration: 1,
      display: "flex",
      opacity: 1,
    })
    .to(
      container,
      {
        duration: 1,
        backgroundImage: "url('Assets/candleon.jpg')",
      },
      "-=1"
    );
});

// Redirecting user to login page after successful account creation
var redirected = false;
setInterval(function () {
  if (
    $(".password-guidelines").html() == "Account created successfully." &&
    redirected == false
  ) {
    redirected = true;
    const redirect = gsap.timeline();
    redirect
      .to(container, {
        duration: 1,
        backgroundImage: "url('Assets/candleoff.jpg')",
      })
      .to(
        ".signup",
        {
          duration: 1,
          display: "none",
          opacity: 0,
        },
        "-=1"
      )
      .to(
        "#scroll-paper",
        {
          duration: 1,
          height: 18,
        },
        "-=.25"
      )
      .to("#scroll-paper", {
        duration: 1,
        height: 283,
        delay: 0.25,
      })
      .to(".login", {
        duration: 1,
        display: "flex",
        opacity: 1,
      })
      .to(
        container,
        {
          duration: 1,
          backgroundImage: "url('Assets/candleon.jpg')",
        },
        "-=1"
      );
  }
}, 100);

// Input name form
$("#status-signup").on("click", "#next-signup", function () {
  const inputNameTl = gsap.timeline();
  inputNameTl
    .to(container, {
      duration: 1,
      backgroundImage: "url('Assets/candleoff.jpg')",
    })
    .to(
      ".signup",
      {
        duration: 1,
        display: "none",
        opacity: 0,
      },
      "-=1"
    )
    .to(
      "#scroll-paper",
      {
        duration: 1,
        height: 18,
      },
      "-=.25"
    )
    .to("#scroll-paper", {
      duration: 1,
      height: 283,
      delay: 0.25,
    })
    .to(".nameInput", {
      duration: 1,
      display: "flex",
      opacity: 1,
    })
    .to(
      container,
      {
        duration: 1,
        backgroundImage: "url('Assets/candleon.jpg')",
      },
      "-=1"
    );
});

// next button for input name
$("#status-name").on("click", "#next-signup-name", function () {
  openLoginCloseName();
});

function openLoginCloseName() {
  const skipNameTl = gsap.timeline();
  skipNameTl
    .to(container, {
      duration: 1,
      backgroundImage: "url('Assets/candleoff.jpg')",
    })
    .to(
      ".nameInput",
      {
        duration: 1,
        display: "none",
        opacity: 0,
      },
      "-=1"
    )
    .to(
      "#scroll-paper",
      {
        duration: 1,
        height: 18,
      },
      "-=.25"
    )
    .to("#scroll-paper", {
      duration: 1,
      height: 283,
      delay: 0.25,
    })
    .to(".login", {
      duration: 1,
      display: "flex",
      opacity: 1,
    })
    .to(
      container,
      {
        duration: 1,
        backgroundImage: "url('Assets/candleon.jpg')",
      },
      "-=1"
    );
}

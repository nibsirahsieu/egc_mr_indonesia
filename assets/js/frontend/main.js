import Alpine from 'alpinejs'
import focus from "@alpinejs/focus"
import JustValidate from "just-validate"
import collapse from "@alpinejs/collapse"
import {
  createIcons,
  Menu,
  ArrowRight,
  ChevronDown,
  Cpu,
  Sprout,
  HeartPulse,
  Building2,
  Quote,
  ArrowUpRight,
  Briefcase,
  Lightbulb,
  ChartNoAxesCombined,
  ListTodo,
  UserCog,
  TriangleAlert,
  Puzzle,
  Home,
  ChevronLeft,
  ChevronRight,
  SquareArrowOutUpRight,
  Sparkles,
  UsersRound,
  SquareCheckBig,
  ChartCandlestick,
  AlignVerticalDistributeEnd,
  Handshake,
  TrendingUp,
  Merge,
  Mail,
  Link,
  Waypoints,
  Grid2x2Check,
  Download,
  LoaderCircle,
  HardHat,
  Boxes,
  Pin,
  Leaf,
  Truck,
  Gauge,
  Search,
  X,
  ArrowLeft
} from "lucide"

createIcons({
  icons: {
    Home,
    Menu,
    ArrowRight,
    ArrowUpRight,
    ChevronDown,
    Download,
    ChevronLeft,
    ChevronRight,
    Cpu,
    Sprout,
    HeartPulse,
    Building2,
    Quote,
    Briefcase,
    Lightbulb,
    ChartNoAxesCombined,
    ListTodo,
    UserCog,
    TriangleAlert,
    Puzzle,
    SquareArrowOutUpRight,
    Sparkles,
    UsersRound,
    SquareCheckBig,
    ChartCandlestick,
    AlignVerticalDistributeEnd,
    Handshake,
    TrendingUp,
    Merge,
    Waypoints,
    Grid2x2Check,
    Mail,
    Link,
    LoaderCircle,
    HardHat,
    Boxes,
    Pin,
    Leaf,
    Truck,
    Gauge,
    Search,
    X,
    ArrowLeft
  }
})

Alpine.plugin(collapse)
Alpine.plugin(focus)
Alpine.data("toggleSidebar", () => ({
  showSidebar: false,
  toggleSidebar() {
    this.showSidebar = !this.showSidebar
    if (!this.isScrolled) {
      this.isScrolled = !this.isScrolled
    }
  }
}))
Alpine.data("formHandler", (fromPage = "") => ({
  formData: {
    firstName: "",
    lastName: "",
    companyName: "",
    jobTitle: "",
    country: "",
    phoneNumber: "",
    emailAddress: "",
    message: "",
    recaptcha: "",
    fromPage: fromPage
  },
  responseMessage: "",
  validation: null,
  isLoading: false,
  isSuccess: false,
  init() {
    // Initialize Just-validate on the form
    this.validation = new JustValidate(this.$refs.form, {
      errorFieldCssClass: "is-invalid",
      successFieldCssClass: "is-valid",
      errorLabelCssClass: "text-red-500 text-sm mt-1",
      errorLabelStyle: {
        color: "#ff0000",
        fontSize: "12px"
      }
    })

    // Add validation rules for each field
    this.validation
      .addField('input[name="firstName"]', [
        {
          rule: "required",
          errorMessage: "First name is required"
        }
      ])
      .addField('input[name="lastName"]', [
        {
          rule: "required",
          errorMessage: "Last name is required"
        }
      ])
      .addField('input[name="companyName"]', [
        {
          rule: "required",
          errorMessage: "Company name is required"
        }
      ])
      .addField('input[name="jobTitle"]', [
        {
          rule: "required",
          errorMessage: "Job title is required"
        }
      ])
      .addField('input[name="country"]', [
        {
          rule: "required",
          errorMessage: "Country is required"
        }
      ])
      .addField('input[name="phoneNumber"]', [
        {
          rule: "required",
          errorMessage: "Phone number is required"
        },
        {
          rule: "minLength",
          value: 10,
          errorMessage: "Phone number should be at least 10 digits long"
        }
      ])
      .addField('input[name="emailAddress"]', [
        {
          rule: "required",
          errorMessage: "Email address is required"
        },
        {
          rule: "email",
          errorMessage: "Please enter a valid email address"
        },
        {
          rule: "customRegexp",
          value: /^[^\s@]+@(?!.*\b(gmail|yahoo|hotmail|outlook|icloud|aol|protonmail|zoho)\.[a-z]+\b).*$/i,
          errorMessage: "Please use a corporate email address"
        }
      ])
      .addField('textarea[name="message"]', [
        {
          rule: "required",
          errorMessage: "Message is required"
        }
      ])
  },
  async validateForm() {
    const that = this
    const isValid = await this.validation.isValid
    if (isValid) {
      that.isLoading = true
      const siteKey = document.documentElement.dataset.sitekey;

      grecaptcha.ready(function() {
        grecaptcha
          .execute(siteKey, {action: "inquiry"})
          .then(function(token) {
            that.formData.recaptcha = token
            let action = that.$refs.form.action

            fetch(action, {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest"
              },
              body: JSON.stringify(that.formData)
            })
              .then(response => {
                if (response.ok) {
                  that.$dispatch("notify", {
                    variant: "success",
                    title: "Inquiry has been sent!",
                    message:
                      "Thank you for sending us an inquiry. Your message is successfully submitted. We will reach you as soon as possible."
                  })
                  that.resetForm(that.formData.fromPage)
                } else {
                  response.text().then(err => {
                    const errors = JSON.parse(err)
                    const message = errors.map(e => e.error)[0]

                    that.$dispatch("notify", {
                      variant: "danger",
                      title: "Inquiry is failed to send",
                      message: message
                    })
                  })
                }
                that.isLoading = false
                that.isSuccess = response.ok
              })
              .catch(error => {
                that.isLoading = false
                that.isSuccess = false
                that.$dispatch("notify", {
                  variant: "danger",
                  title: "Inquiry is failed to send",
                  message:
                    "Inquiry is not available right now. Please reach us through our email "
                })
              })
          })
      })
    }
  },
  resetForm(fromPage) {
    this.formData = {
      firstName: "",
      lastName: "",
      companyName: "",
      jobTitle: "",
      country: "",
      phoneNumber: "",
      emailAddress: "",
      message: "",
      recaptcha: "",
      fromPage: fromPage || ""
    }
    this.validation.destroy()
  }
}))
Alpine.data("notification", () => ({
  notifications: [],
  displayDuration: 8000,
  soundEffect: true,

  addNotification({
    variant = "info",
    sender = null,
    title = null,
    message = null
  }) {
    const id = Date.now()
    const notification = { id, variant, sender, title, message }

    // Keep only the most recent 20 notifications
    if (this.notifications.length >= 20) {
      this.notifications.splice(0, this.notifications.length - 19)
    }

    // Add the new notification to the notifications stack
    this.notifications.push(notification)

    if (this.soundEffect) {
      // Play the notification sound
      const notificationSound = new Audio(
        "https://res.cloudinary.com/ds8pgw1pf/video/upload/v1728571480/penguinui/component-assets/sounds/ding.mp3"
      )
      notificationSound.play().catch(error => {
        console.error("Error playing the sound:", error)
      })
    }
  },
  removeNotification(id) {
    setTimeout(() => {
      this.notifications = this.notifications.filter(
        notification => notification.id !== id
      )
    }, 1000)
  }
}))
Alpine.data("formHandlerWhitepaper", (whitepaperId = "") => ({
  formData: {
    firstName: "",
    lastName: "",
    companyName: "",
    jobTitle: "",
    country: "",
    emailAddress: "",
    recaptcha: "",
    whitepaperId: whitepaperId
  },
  responseMessage: "",
  validation: null,
  isLoading: false,
  isSuccess: false,
  init() {
    // Initialize Just-validate on the form
    this.validation = new JustValidate(this.$refs.form, {
      errorFieldCssClass: "is-invalid",
      successFieldCssClass: "is-valid",
      errorLabelCssClass: "text-red-500 text-sm mt-1",
      errorLabelStyle: {
        color: "#ff0000",
        fontSize: "12px"
      }
    })

    // Add validation rules for each field
    this.validation
      .addField('input[name="firstName"]', [
        {
          rule: "required",
          errorMessage: "First name is required"
        }
      ])
      .addField('input[name="lastName"]', [
        {
          rule: "required",
          errorMessage: "Last name is required"
        }
      ])
      .addField('input[name="companyName"]', [
        {
          rule: "required",
          errorMessage: "Company name is required"
        }
      ])
      .addField('input[name="jobTitle"]', [
        {
          rule: "required",
          errorMessage: "Job title is required"
        }
      ])
      .addField('input[name="country"]', [
        {
          rule: "required",
          errorMessage: "Country is required"
        }
      ])
      .addField('input[name="emailAddress"]', [
        {
          rule: "required",
          errorMessage: "Email address is required"
        },
        {
          rule: "email",
          errorMessage: "Please enter a valid email address"
        },
        {
          rule: "customRegexp",
          value: /^[^\s@]+@(?!.*\b(gmail|yahoo|hotmail|outlook|icloud|aol|protonmail|zoho)\.[a-z]+\b).*$/i,
          errorMessage: "Please use a corporate email address"
        }
      ])
  },
  async validateForm() {
    const that = this
    const isValid = await this.validation.isValid
    if (isValid) {
      that.isLoading = true
      const siteKey = document.documentElement.dataset.sitekey;
      grecaptcha.ready(function() {
        grecaptcha
          .execute(siteKey, {action: "request_whitepaper"})
          .then(function(token) {
            that.formData.recaptcha = token
            let action = that.$refs.form.action

            fetch(action, {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest"
              },
              body: JSON.stringify(that.formData)
            })
              .then(response => {
                if (response.ok) {
                  that.$dispatch("notify", {
                    variant: "success",
                    title: "Your download request has been sent!",
                    message:
                      "Thank you for your interest in our whitepaper. Happy reading!."
                  })
                  that.resetForm()
                } else {
                  response.text().then(err => {
                    const errors = JSON.parse(err)
                    const message = errors.map(e => e.error)[0]

                    that.$dispatch("notify", {
                      variant: "danger",
                      title: "Downlaod request is failed to send",
                      message: message
                    })
                  })
                }

                that.isLoading = false
                that.isSuccess = response.ok
              })
              .catch(error => {
                that.isLoading = false
                that.isSuccess = false
                that.$dispatch("notify", {
                  variant: "danger",
                  title: "Downlaod request is failed to send",
                  message:
                    "Whitepapar is not available right now. Please reach us through our email "
                })
              })
          })
      })
    }
  },
  resetForm() {
    this.formData = {
      firstName: "",
      lastName: "",
      companyName: "",
      jobTitle: "",
      country: "",
      emailAddress: "",
      recaptcha: "",
      whitepaperId: ""
    }
  }
}))
Alpine.data("tocComponent", () => ({
  activeHeading: null,
  init() {
    const headings = document.querySelectorAll("h2, h3, h4")

    // Track scroll position and heading positions
    const getHeadingPositions = () => {
      return Array.from(headings).map(heading => ({
        id: heading.id,
        top: heading.getBoundingClientRect().top + window.scrollY - 100 // Offset for fixed header
      }))
    }

    // Find active heading based on scroll position
    const updateActiveHeading = () => {
      const scrollPosition = window.scrollY
      const positions = getHeadingPositions()

      // Find the heading that's currently past the scroll position
      const activeHeading = positions.find((heading, index) => {
        const nextHeading = positions[index + 1]
        if (!nextHeading) return true

        return (
          scrollPosition >= heading.top && scrollPosition < nextHeading.top
        )
      })

      if (activeHeading) {
        this.activeHeading = activeHeading.id
      }
    }

    // Update on scroll (with debounce for performance)
    let timeout
    window.addEventListener("scroll", () => {
      if (timeout) {
        window.cancelAnimationFrame(timeout)
      }
      timeout = window.requestAnimationFrame(() => {
        updateActiveHeading()
      })
    })

    // Initial update
    updateActiveHeading()
  }
}))

window.Alpine = Alpine
Alpine.start()

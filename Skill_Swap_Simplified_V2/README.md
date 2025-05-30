# SkillSwap

A simplified skill-sharing website that allows users to share and learn skills from others in their community.

## Features

- User authentication (login/signup)
- Browse and search skills
- Add and manage your own skills
- Responsive design using Bootstrap 5
- Ready for PHP backend integration

## Project Structure

```
SkillSwap/
├── index.html              # Landing page
├── explore.html            # Browse skills
├── login.html              # Login form
├── signup.html             # Signup form
├── dashboard.html          # User dashboard
├── css/
│   └── custom.css          # Custom styles
├── js/
│   ├── auth.js             # Authentication logic
│   ├── explore.js          # Explore page logic
│   └── dashboard.js        # Dashboard logic
├── assets/
│   └── images/             # Images and icons
└── README.md
```

## Technologies Used

- HTML5
- CSS3
- JavaScript (ES6+)
- Bootstrap 5
- Bootstrap Icons
- localStorage for data persistence

## Getting Started

1. Clone the repository
2. Open `index.html` in your web browser
3. For development with PHP:
   - Place the project in your XAMPP's `htdocs` directory
   - Access via `http://localhost/SkillSwap`

## Features Ready for PHP Integration

- All forms use `action="#"` and `method="POST"` for easy backend hooking
- Modular file structure for easy PHP integration
- Form validation on both client and server side
- Session management ready for PHP sessions

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details. 
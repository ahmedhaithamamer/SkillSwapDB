# SkillSwap Simplified

A skill-sharing platform designed for university students to exchange knowledge and skills without financial barriers. SkillSwap enables students to connect with peers, share expertise, and acquire new skills through mutual exchange.

## About

SkillSwap was created for students at Nile University to:
- Exchange skills on a time-for-time basis
- Connect with peers who have complementary skills
- Learn new skills outside their major without financial cost
- Share their knowledge and expertise with others

## Features

- User authentication (login/signup) with university email validation
- Responsive dashboard with skills management
- Interactive marketplace to browse, search, and filter available skills
- Skill categorization by discipline, level, and academic major
- User profile management
- Direct messaging system between students
- Skills tracking for both teaching and learning

## Project Structure

```
SkillSwap/
├── index.html              # Landing page
├── about.html              # About us page with team information
├── marketplace.html        # Browse and search available skills
├── dashboard.html          # User dashboard for skills management
├── login.html              # Login form
├── signup.html             # Signup form
├── css/
│   └── custom.css          # Custom styles for the platform
├── js/
│   ├── auth.js             # Authentication and user management
│   ├── marketplace.js      # Marketplace functionality
│   └── dashboard.js        # Dashboard functionality
├── assets/
│   └── images/             # Images for the website
├── sql/
│   └── [empty]             # Reserved for database scripts
└── includes/
    └── [empty]             # Reserved for PHP components
```

## Technologies Used

- **Frontend:**
  - HTML5
  - CSS3 with custom styling
  - JavaScript (ES6+)
  - Bootstrap 5 for responsive design
  - Bootstrap Icons for UI elements
  - HTML Templates for component rendering
  
- **Data Management:**
  - localStorage for client-side data persistence (demo purposes)
  - JSON data structures for skill and user information

## Key Implementation Features

- **HTML Templates**: Using native `<template>` elements for repeatable components
- **Modular JavaScript**: Separation of concerns between authentication, marketplace, and dashboard functionalities
- **Bootstrap Offcanvas**: Simplified sidebar implementation using Bootstrap's native components
- **Responsive Design**: Mobile-friendly interface that works on all device sizes
- **University Email Validation**: Ensures platform is only used by university students

## Design Principles

The platform follows these core principles:
- **Community:** Creating networks that extend beyond the classroom
- **Accessibility:** Removing financial barriers to learning through a time-exchange system
- **Reciprocity:** Valuing the exchange of knowledge as a balanced ecosystem

## Getting Started

1. Clone the repository
2. Open `index.html` in your web browser to view the landing page
3. Use the signup page to create an account (must use a Nile University email)
4. Login to access the dashboard and marketplace

## Development Roadmap

The project is structured to allow future integration with a backend system:
- The `sql/` directory is reserved for database scripts
- The `includes/` directory is prepared for PHP components
- All forms use the appropriate structure for future server-side processing
- User authentication is ready to be connected to a proper backend system

## Features Ready for Backend Integration

- User authentication with email verification
- Skills marketplace with filtering and search
- User profile management
- Skills management (adding, editing, removing)
- Messaging system between users

## Potential Enhancements

- **Server Backend Implementation**: Replace localStorage with a proper database
- **Real-time Messaging**: Add WebSocket support for real-time chat between users
- **Rating System**: Add the ability to rate and review skill exchanges
- **Skill Categories Management**: Admin interface to manage skill categories
- **Email Notifications**: Implement email notifications for new connections
- **Mobile App**: Convert to a Progressive Web App or native mobile app

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Team

- Ahmed Haitham - Founder & Lead Developer
- Yehia Zakaria - UX Designer
- Abdelrahman El-Sisi - Community Manager
- Youssef Waleed - Backend Developer
- Omar El-Beltagy - Database Engineer
- Abdullah Emam - Frontend Developer

## License

This project is licensed under the MIT License. 

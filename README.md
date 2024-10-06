# Personal Password Manager

This is a personal project. I created this password manager website because I needed a place or tool to store all the accounts I have and can be accessed anywhere. Then I created this website

## 🌟 Features

- **Secure Authentication**: Protected login system
- **Password Encryption**: Implementation of Vigenere cipher encryption
- **User-Friendly Dashboard**: Modern and responsive interface
- **Password Management**:
  - Add new password entries
  - Edit existing passwords
  - Delete password entries
  - Show/hide password functionality
- **Search Functionality**: Quick search through stored passwords
- **Export Feature**: Export passwords to Excel format
- **Mobile Responsive**: Works seamlessly on all devices

## 🛠️ Technologies Used

- **Backend**: PHP
- **Database**: MySQL
- **Frontend**:
  - HTML5
  - CSS3
  - JavaScript
- **Libraries/Frameworks**:
  - Bootstrap 5.3
  - Font Awesome 6.0
  - SweetAlert2

## 📁 Project Structure

```
password-manager/
├── add.php          # Add new password entry
├── dashboard.php    # Main dashboard interface
├── edit.php         # Edit password entries
├── enkripsi.php     # Encryption logic
├── hapus.php        # Delete functionality
├── index.php        # Entry point
├── koneksi.php      # Database connection
├── logout.php       # Logout handling
└── session.php      # Session management
```

## 🔧 Installation & Setup

1. Clone the repository
```bash
git clone https://github.com/Zachry2906/passmanager-web.git
```

2. Set up your web server (Apache/Nginx) and ensure PHP is installed

3. Configure your database connection in `koneksi.php`

4. Import the database schema (SQL file provided)

5. Access the application through your web server

## 🚀 Deployment

The application is currently deployed using InfinityFree, making it accessible from anywhere with an internet connection.

## 🔐 Security Features

- Session-based authentication
- Vigenere cipher encryption for password storage
- Secure password visibility toggle
- Protection against unauthorized access

## 💡 Usage

1. Login with your credentials
2. Use the dashboard to manage your passwords
3. Add new passwords using the "Tambah Password" button
4. Search for specific entries using the search box
5. Export your password database to Excel when needed
6. Use the eye icon to toggle password visibility

## ⚠️ Important Notes

- This is a personal project intended for individual use
- Remember to keep your master password secure
- Regularly backup your password database
- Use HTTPS in production environment

## 📝 Future Improvements (kalo tidak malas ngoding)

- [ ] Add password generation feature
- [ ] Implement automatic backup system

## 🤝 Contributing

This is a personal project, but suggestions and improvements are welcome. Feel free to fork and submit pull requests.

## 📜 License

This project is licensed under the MIT License - see the LICENSE file for details.

---
⚡️ Created with security and simplicity in mind

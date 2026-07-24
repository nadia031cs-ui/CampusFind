const express = require('express');
const router = express.Router();
const { login, signup } = require('../controllers/authController');
const protect = require('../middlewares/authMiddleware');

router.post('/login', login);
router.post('/signup', signup);

// test protected route
router.get('/profile', protect, (req, res) => {
  res.json({ message: 'This is protected data', user: req.user });
});

module.exports = router;
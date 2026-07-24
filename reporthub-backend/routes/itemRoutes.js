const express = require('express');
const router = express.Router();
const { createItem, getFeed } = require('../controllers/itemController');
const protect = require('../middlewares/authMiddleware');

router.post('/create', protect, createItem);   
router.get('/feed', getFeed);                  

module.exports = router;
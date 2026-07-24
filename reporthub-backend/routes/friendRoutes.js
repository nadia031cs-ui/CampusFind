const express = require('express');
const router = express.Router();
const {
  sendRequest,
  getMyRequests,
  respondToRequest,
  getFriendList
} = require('../controllers/friendController');
const protect = require('../middlewares/authMiddleware');

router.post('/request', protect, sendRequest);
router.get('/requests', protect, getMyRequests);
router.post('/respond', protect, respondToRequest);
router.get('/list', protect, getFriendList);

module.exports = router;s
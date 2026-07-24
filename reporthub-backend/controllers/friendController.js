let friendRequests = []; // { id, from, to, status }
let friendships = [];    // { user1, user2 }
let reqIdCounter = 1;

const sendRequest = (req, res) => {
  const fromEmail = req.user.email;
  const { toEmail } = req.body;

  if (!toEmail) {
    return res.status(400).json({ message: 'toEmail is required' });
  }

  if (fromEmail === toEmail) {
    return res.status(400).json({ message: "You can't send a request to yourself" });
  }

  const alreadySent = friendRequests.find(
    r => r.from === fromEmail && r.to === toEmail && r.status === 'pending'
  );
  if (alreadySent) {
    return res.status(400).json({ message: 'Request already sent' });
  }

  const newRequest = {
    id: reqIdCounter++,
    from: fromEmail,
    to: toEmail,
    status: 'pending'
  };
  friendRequests.push(newRequest);

  res.status(201).json({ message: 'Friend request sent', request: newRequest });
};

const getMyRequests = (req, res) => {
  const myEmail = req.user.email;
  const incoming = friendRequests.filter(r => r.to === myEmail && r.status === 'pending');
  res.json({ requests: incoming });
};

const respondToRequest = (req, res) => {
  const myEmail = req.user.email;
  const { requestId, action } = req.body; // action: 'accept' or 'reject'

  const request = friendRequests.find(r => r.id === requestId && r.to === myEmail);
  if (!request) {
    return res.status(404).json({ message: 'Request not found' });
  }

  if (action === 'accept') {
    request.status = 'accepted';
    friendships.push({ user1: request.from, user2: request.to });
  } else {
    request.status = 'rejected';
  }

  res.json({ message: `Request ${action}ed`, request });
};

const getFriendList = (req, res) => {
  const myEmail = req.user.email;
  const myFriends = friendships
    .filter(f => f.user1 === myEmail || f.user2 === myEmail)
    .map(f => (f.user1 === myEmail ? f.user2 : f.user1));

  res.json({ friends: myFriends });
};

module.exports = { sendRequest, getMyRequests, respondToRequest, getFriendList };
let items = [];
let itemIdCounter = 1;

const createItem = (req, res) => {
  const { title, description, location, category } = req.body;

  if (!title || !description || !location) {
    return res.status(400).json({ message: 'Title, description, and location are required' });
  }

  const newItem = {
    id: itemIdCounter++,
    title,
    description,
    location,
    category: category || 'general',
    postedBy: req.user.email,
    createdAt: new Date()
  };

  items.push(newItem);
  res.status(201).json({ message: 'Item posted successfully', item: newItem });
};

const getFeed = (req, res) => {
  res.json({ items: items.reverse() });
};

module.exports = { createItem, getFeed };
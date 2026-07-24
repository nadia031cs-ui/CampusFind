require('dotenv').config();
const express = require('express');
const app = express();

app.use(express.json());

const authRoutes = require('./routes/authRoutes');
app.use('/api/auth', authRoutes);

const itemRoutes = require('./routes/itemRoutes');
app.use('/api/items', itemRoutes);

app.get('/', (req, res) => {
  res.send('ReportHub API is running');
});

const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
  console.log(`Server running on http://localhost:${PORT}`);
});
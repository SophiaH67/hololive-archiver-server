require("dotenv").config();
import express, { Request, Response } from "express";
import assert from "assert";
import mongoose from "mongoose";
import cors from "cors";

assert(process.env.MONGO_URI);
mongoose.connect(process.env.MONGO_URI);

const app = express();
app.use(cors());

app.get("/", (_req: Request, res: Response) => {
  res.send("Hello World!");
});

const port = parseInt(process.env.PORT || "3000");
app.listen(port, () => {
  console.log(`Example app listening on 0.0.0.0:${port}`);
});

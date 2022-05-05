import { Schema, model, Document } from "mongoose";

export interface DownloadRequest extends Document {
  url: string;
  output_folder: string;
  platform: string;
  completedAt: Date;
  jobs: {
    url: string;
    success: boolean;
    heartbeat_at: Date;
  }[];
}

const DownloadRequestSchema = new Schema(
  {
    url: { type: String, required: true },
    output_folder: { type: String, required: true },
    platform: { type: String, required: true },
    completedAt: { type: Date, required: false },
    jobs: [
      {
        url: { type: String, required: true },
        success: { type: Boolean, required: true },
        heartbeatAt: { type: Date, required: true },
      },
    ],
  },
  { timestamps: true }
);
export const DownloadRequestModel = model<DownloadRequest>(
  "DownloadRequest",
  DownloadRequestSchema
);

// src/components/TaskNestIcon.tsx

import React from "react";

interface TaskNestIconProps {
  size?: number;              // Size in px (optional)
  primaryColor?: string;       // Main color (nest + check)
  textColor?: string;          // Text color
}

const TaskNestIcon: React.FC<TaskNestIconProps> = ({
  size = 100,
  primaryColor = "#FACC15", // Yellow
  textColor = "#ffffff",     // White
}) => {
  return (
    <svg
      width={size * 3.5}
      height={size}
      viewBox="0 0 350 100"
      xmlns="http://www.w3.org/2000/svg"
    >
      {/* Circle (Nest) */}
      <circle
        cx="50"
        cy="50"
        r="30"
        stroke={primaryColor}
        strokeWidth="5"
        fill="none"
      />

      {/* Checkmark */}
      <polyline
        points="35,50 48,63 68,35"
        fill="none"
        stroke={primaryColor}
        strokeWidth="5"
        strokeLinecap="round"
        strokeLinejoin="round"
      />

      {/* TaskNest Text */}
      <text
        x="90"
        y="65"
        fontFamily="Arial, Helvetica, sans-serif"
        fontSize="40"
        fontWeight="bold"
        fill={textColor}
      >
        TaskNest
      </text>
    </svg>
  );
};

export default TaskNestIcon;

-- Adds the dashboard welcome-disclaimer flag.
-- Safe to skip if PHP has already created the column on first dashboard visit.

ALTER TABLE `users`
  ADD `disclaimer_agreed` tinyint(1) NOT NULL DEFAULT 0;

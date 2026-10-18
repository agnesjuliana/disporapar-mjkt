-- disporapar_db02.categories definition

CREATE TABLE `categories` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- disporapar_db02.migrations definition

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- disporapar_db02.users definition

CREATE TABLE `users` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('ADMIN','EVENT_ORGANIZER','TENANT','MASYARAKAT') NOT NULL,
  `status` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `email_verification_otp_hash` varchar(255) DEFAULT NULL,
  `email_verification_otp_expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- disporapar_db02.venues definition

CREATE TABLE `venues` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `lowest_price` decimal(15,2) NOT NULL,
  `highest_price` decimal(15,2) NOT NULL,
  `available_from` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `available_to` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- disporapar_db02.event_organizers definition

CREATE TABLE `event_organizers` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `organization_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `contact_phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_organizers_user_id_foreign` (`user_id`),
  CONSTRAINT `event_organizers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- disporapar_db02.events definition

CREATE TABLE `events` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `organizer_id` varchar(255) NOT NULL,
  `category_id` varchar(255) NOT NULL,
  `venue_type` enum('INTERNAL','EXTERNAL') NOT NULL,
  `venue_id` varchar(255) DEFAULT NULL,
  `external_venue_name` varchar(255) DEFAULT NULL,
  `external_venue_address` text DEFAULT NULL,
  `external_venue_capacity` int(11) DEFAULT NULL,
  `event_start` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `event_end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `registration_deadline` timestamp NULL DEFAULT NULL,
  `slot_size` int(11) NOT NULL,
  `capacity` int(11) NOT NULL,
  `banner_url` text DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `events_organizer_id_foreign` (`organizer_id`),
  KEY `events_venue_id_foreign` (`venue_id`),
  KEY `events_approved_by_foreign` (`approved_by`),
  KEY `events_category_id_foreign` (`category_id`),
  CONSTRAINT `events_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `events_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `events_organizer_id_foreign` FOREIGN KEY (`organizer_id`) REFERENCES `event_organizers` (`id`),
  CONSTRAINT `events_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- disporapar_db02.participant_registration definition

CREATE TABLE `participant_registration` (
  `id` varchar(255) NOT NULL,
  `event_id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `attendance_status` enum('NOT_CHECKED_IN','PRESENT','ABSENT') NOT NULL,
  `checked_in_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `participant_registration_event_id_foreign` (`event_id`),
  KEY `participant_registration_user_id_foreign` (`user_id`),
  CONSTRAINT `participant_registration_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  CONSTRAINT `participant_registration_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- disporapar_db02.tenants definition

CREATE TABLE `tenants` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `organization_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `contact_phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `registration_status` varchar(255) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `approved_by` varchar(255) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenants_user_id_foreign` (`user_id`),
  KEY `tenants_approved_by_foreign` (`approved_by`),
  CONSTRAINT `tenants_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `tenants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- disporapar_db02.user_category_interests definition

CREATE TABLE `user_category_interests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) NOT NULL,
  `category_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_category_interests_user_id_category_id_unique` (`user_id`,`category_id`),
  KEY `user_category_interests_category_id_foreign` (`category_id`),
  CONSTRAINT `user_category_interests_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `user_category_interests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- disporapar_db02.venue_bookings definition

CREATE TABLE `venue_bookings` (
  `id` varchar(255) NOT NULL,
  `venue_id` varchar(255) NOT NULL,
  `organizer_id` varchar(255) NOT NULL,
  `event_id` varchar(255) DEFAULT NULL,
  `booking_start` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `booking_end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `booking_type` varchar(255) NOT NULL DEFAULT 'HOURLY',
  `status` varchar(255) NOT NULL,
  `requested_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `approved_by` varchar(255) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `final_price` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `venue_bookings_venue_id_foreign` (`venue_id`),
  KEY `venue_bookings_organizer_id_foreign` (`organizer_id`),
  KEY `venue_bookings_event_id_foreign` (`event_id`),
  KEY `venue_bookings_approved_by_foreign` (`approved_by`),
  CONSTRAINT `venue_bookings_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `venue_bookings_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  CONSTRAINT `venue_bookings_organizer_id_foreign` FOREIGN KEY (`organizer_id`) REFERENCES `event_organizers` (`id`),
  CONSTRAINT `venue_bookings_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- disporapar_db02.event_click_logs definition

CREATE TABLE `event_click_logs` (
  `id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `event_id` varchar(255) NOT NULL,
  `category_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_click_logs_user_id_foreign` (`user_id`),
  KEY `event_click_logs_event_id_foreign` (`event_id`),
  KEY `event_click_logs_category_id_foreign` (`category_id`),
  CONSTRAINT `event_click_logs_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `event_click_logs_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  CONSTRAINT `event_click_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- disporapar_db02.event_registrations definition

CREATE TABLE `event_registrations` (
  `id` varchar(255) NOT NULL,
  `event_id` varchar(255) NOT NULL,
  `tenant_id` varchar(255) NOT NULL,
  `registration_status` enum('PENDING','APPROVED','REJECTED','CANCELLED') NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `approved_by` varchar(255) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `requested_slot_count` int(10) unsigned NOT NULL DEFAULT 1,
  `requested_slot_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`requested_slot_ids`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_registrations_event_id_foreign` (`event_id`),
  KEY `event_registrations_tenant_id_foreign` (`tenant_id`),
  KEY `event_registrations_approved_by_foreign` (`approved_by`),
  CONSTRAINT `event_registrations_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `event_registrations_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`),
  CONSTRAINT `event_registrations_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- disporapar_db02.event_slots definition

CREATE TABLE `event_slots` (
  `id` varchar(255) NOT NULL,
  `event_id` varchar(255) NOT NULL,
  `slot_number` int(11) NOT NULL,
  `slot_label` varchar(100) DEFAULT NULL,
  `date_start` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `slot_width` decimal(8,2) DEFAULT NULL,
  `slot_long` decimal(8,2) DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `is_booked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_slots_event_id_foreign` (`event_id`),
  CONSTRAINT `event_slots_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- disporapar_db02.registration_attendances definition

CREATE TABLE `registration_attendances` (
  `id` varchar(255) NOT NULL,
  `event_registration_id` varchar(255) NOT NULL,
  `attendance_status` enum('NOT_CHECKED_IN','PRESENT','ABSENT') NOT NULL,
  `checked_in_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `registration_attendances_event_registration_id_foreign` (`event_registration_id`),
  CONSTRAINT `registration_attendances_event_registration_id_foreign` FOREIGN KEY (`event_registration_id`) REFERENCES `event_registrations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- disporapar_db02.registration_documents definition

CREATE TABLE `registration_documents` (
  `id` varchar(255) NOT NULL,
  `event_registration_id` varchar(255) NOT NULL,
  `document_type` enum('KTP','KK','PHOTO','BUSINESS_LICENSE','OTHER') NOT NULL,
  `file_url` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `registration_documents_event_registration_id_foreign` (`event_registration_id`),
  CONSTRAINT `registration_documents_event_registration_id_foreign` FOREIGN KEY (`event_registration_id`) REFERENCES `event_registrations` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- disporapar_db02.registration_slots definition

CREATE TABLE `registration_slots` (
  `id` varchar(255) NOT NULL,
  `event_registration_id` varchar(255) NOT NULL,
  `slot_id` varchar(255) NOT NULL,
  `status` enum('ASSIGNED','CANCELLED') NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `registration_slots_event_registration_id_foreign` (`event_registration_id`),
  KEY `registration_slots_slot_id_foreign` (`slot_id`),
  CONSTRAINT `registration_slots_event_registration_id_foreign` FOREIGN KEY (`event_registration_id`) REFERENCES `event_registrations` (`id`),
  CONSTRAINT `registration_slots_slot_id_foreign` FOREIGN KEY (`slot_id`) REFERENCES `event_slots` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
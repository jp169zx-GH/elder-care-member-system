
-- yanglao_db.sql 放进 database 文件夹，数据库一键导入即可
sql
-- ====================== 养老会员系统 完整数据库 ======================
SET NAMES utf8mb4;
DROP DATABASE IF EXISTS yanglao_db;
CREATE DATABASE yanglao_db DEFAULT CHARACTER SET utf8mb4;
USE yanglao_db;

-- 1.会员基础表
CREATE TABLE member_info (
  member_id VARCHAR(32) PRIMARY KEY COMMENT '会员唯一ID',
  member_name NVARCHAR(20) NOT NULL,
  member_idcard VARCHAR(18),
  member_age INT,
  member_phone VARCHAR(11),
  member_status TINYINT DEFAULT 1,
  create_time DATETIME DEFAULT CURRENT_TIMESTAMP,
  operator NVARCHAR(20)
)COMMENT='会员基础信息表';

-- 2.健康档案
CREATE TABLE health_archive (
  health_id VARCHAR(32) PRIMARY KEY,
  member_id VARCHAR(32) NOT NULL,
  body_height INT,
  body_weight DECIMAL(5,1),
  blood_pressure_high INT,
  blood_pressure_low INT,
  blood_sugar DECIMAL(4,1),
  chronic_disease NVARCHAR(500),
  physical_check_result TEXT,
  health_level TINYINT,
  archive_createtime DATETIME DEFAULT CURRENT_TIMESTAMP,
  archive_updatetime DATETIME NULL ON UPDATE CURRENT_TIMESTAMP
)COMMENT='健康档案表';

-- 3.活动记录
CREATE TABLE member_activity (
  activity_id VARCHAR(32) PRIMARY KEY,
  member_id VARCHAR(32) NOT NULL,
  activity_date DATE,
  activity_content NVARCHAR(500),
  activity_state NVARCHAR(50),
  record_person NVARCHAR(20),
  record_time DATETIME DEFAULT CURRENT_TIMESTAMP
)COMMENT='会员活动记录表';

-- 4.治疗方案
CREATE TABLE treat_plan (
  treat_id VARCHAR(32) PRIMARY KEY,
  member_id VARCHAR(32) NOT NULL,
  treat_title NVARCHAR(100),
  treat_target TEXT,
  treat_content TEXT,
  treat_start_date DATE,
  treat_end_date DATE,
  treat_doctor NVARCHAR(30),
  treat_status TINYINT DEFAULT 1
)COMMENT='康养治疗方案';

-- 5.用药记录
CREATE TABLE drug_record (
  drug_id VARCHAR(32) PRIMARY KEY,
  member_id VARCHAR(32) NOT NULL,
  drug_name NVARCHAR(100),
  drug_spec NVARCHAR(100),
  drug_dosage NVARCHAR(50),
  drug_frequency NVARCHAR(50),
  use_start_date DATE,
  use_end_date DATE,
  drug_note NVARCHAR(300),
  record_operator NVARCHAR(20),
  create_time DATETIME DEFAULT CURRENT_TIMESTAMP
)COMMENT='用药记录表';

-- 6.会员缴费表
CREATE TABLE member_pay (
  pay_id VARCHAR(32) PRIMARY KEY,
  member_id VARCHAR(32) NOT NULL,
  pay_type TINYINT,
  pay_money DECIMAL(12,2),
  pay_year VARCHAR(20),
  pay_status TINYINT DEFAULT 0,
  pay_time DATETIME,
  pay_channel NVARCHAR(50),
  receipt_no VARCHAR(30)
)COMMENT='缴费记录表';

-- 7.保证金账户
CREATE TABLE member_bond (
  bond_id VARCHAR(32) PRIMARY KEY,
  member_id VARCHAR(32) NOT NULL,
  bond_total DECIMAL(12,2),
  bond_balance DECIMAL(12,2),
  bond_freeze DECIMAL(12,2) DEFAULT 0,
  bond_status TINYINT DEFAULT 1,
  pay_bond_time DATETIME,
  refund_apply_time DATETIME NULL,
  sync_bank_status TINYINT DEFAULT 0
)COMMENT='保证金账户表';

-- 8.银行托管同步表
CREATE TABLE bank_bond_sync (
  sync_id VARCHAR(32) PRIMARY KEY,
  member_id VARCHAR(32) NOT NULL,
  system_balance DECIMAL(12,2),
  bank_balance DECIMAL(12,2),
  change_money DECIMAL(12,2),
  change_type TINYINT,
  bank_account_no VARCHAR(30),
  sync_time DATETIME DEFAULT CURRENT_TIMESTAMP,
  sync_result NVARCHAR(20)
)COMMENT='银行对账同步表';

-- 9.房源表
CREATE TABLE house_room (
  room_id VARCHAR(32) PRIMARY KEY,
  room_type NVARCHAR(30),
  room_floor NVARCHAR(20),
  room_price DECIMAL(10,2),
  room_status TINYINT DEFAULT 1,
  hotel_sync_status TINYINT DEFAULT 0
)COMMENT='康养房源表';

-- 10.入住记录
CREATE TABLE member_checkin (
  checkin_id VARCHAR(32) PRIMARY KEY,
  member_id VARCHAR(32) NOT NULL,
  room_id VARCHAR(32) NOT NULL,
  checkin_date DATE,
  checkout_date DATE,
  live_status TINYINT DEFAULT 1,
  hotel_order_no VARCHAR(32),
  hotel_account_id VARCHAR(32)
)COMMENT='入住记录表';

-- 11.餐饮记录
CREATE TABLE member_food (
  food_id VARCHAR(32) PRIMARY KEY,
  member_id VARCHAR(32) NOT NULL,
  food_package NVARCHAR(50),
  eat_date DATE,
  eat_breakfast TINYINT DEFAULT 0,
  eat_lunch TINYINT DEFAULT 0,
  eat_dinner TINYINT DEFAULT 0,
  food_money DECIMAL(10,2),
  hotel_sync TINYINT DEFAULT 0
)COMMENT='餐饮消费表';

-- 12.酒店对接对账表
CREATE TABLE hotel_data_sync (
  hotel_sync_id VARCHAR(32) PRIMARY KEY,
  order_no VARCHAR(32),
  room_id VARCHAR(32),
  member_id VARCHAR(32),
  system_consume DECIMAL(12,2),
  hotel_consume DECIMAL(12,2),
  diff_money DECIMAL(12,2) DEFAULT 0,
  sync_type TINYINT,
  sync_time DATETIME DEFAULT CURRENT_TIMESTAMP,
  check_status TINYINT DEFAULT 0
)COMMENT='酒店对接对账表';

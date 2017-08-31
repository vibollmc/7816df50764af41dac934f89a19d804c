using System;

namespace JavCrawl.Models.DbEntity
{
    public partial class Users
    {
        public int Id { get; set; }
        public string Address { get; set; }
        public string Avatar { get; set; }
        public DateTime? CreatedAt { get; set; }
        public DateTime? DeletedAt { get; set; }
        public string Email { get; set; }
        public int? FtpId { get; set; }
        public string FullName { get; set; }
        public int? GroupId { get; set; }
        public string Password { get; set; }
        public string PhoneNumber { get; set; }
        public int? RoleId { get; set; }
        public sbyte? Status { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public string Username { get; set; }
    }
}

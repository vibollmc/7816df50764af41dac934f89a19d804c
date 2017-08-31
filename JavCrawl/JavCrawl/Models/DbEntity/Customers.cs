using System;

namespace JavCrawl.Models.DbEntity
{
    public partial class Customers
    {
        public int Id { get; set; }
        public int? BanedTo { get; set; }
        public DateTime? CreatedAt { get; set; }
        public string Data { get; set; }
        public DateTime? DeletedAt { get; set; }
        public string Email { get; set; }
        public string FullName { get; set; }
        public string Password { get; set; }
        public string Phone { get; set; }
        public string RememberToken { get; set; }
        public int? Spamed { get; set; }
        public sbyte? Status { get; set; }
        public int? TypeId { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public string Username { get; set; }
        public sbyte? Verified { get; set; }
    }
}

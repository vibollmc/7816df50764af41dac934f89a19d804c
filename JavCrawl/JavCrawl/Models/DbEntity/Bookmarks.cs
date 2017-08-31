namespace JavCrawl.Models.DbEntity
{
    public partial class Bookmarks
    {
        public int Id { get; set; }
        public int? AbleId { get; set; }
        public string Type { get; set; }
        public int? UserId { get; set; }
    }
}

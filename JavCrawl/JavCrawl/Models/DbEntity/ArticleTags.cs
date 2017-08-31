namespace JavCrawl.Models.DbEntity
{
    public partial class ArticleTags
    {
        public int Id { get; set; }
        public int? ArticleId { get; set; }
        public int? TagId { get; set; }
    }
}

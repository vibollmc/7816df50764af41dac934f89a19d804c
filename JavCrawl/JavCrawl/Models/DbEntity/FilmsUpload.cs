using System;

namespace JavCrawl.Models.DbEntity
{
    public class FilmsUpload
    {
        public int Id { get; set; }
        public int FilmId { get; set; }
        public ServerUpload Server { get; set; }
        public DateTime? CreatedAt { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public StatusUpload Status { get; set; }
        public string RemoteId { get; set; }
    }

    public enum ServerUpload
    {
        BitPorno = 1,
        OpenLoad = 2,
        StreamCherry = 3
    }

    public enum StatusUpload
    {
        Uploading = 1,
        Done = 2,
        Error = 3
    }
}

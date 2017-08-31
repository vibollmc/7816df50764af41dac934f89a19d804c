namespace JavCrawl.Models.StreamCherry
{
    public class StreamCherryResult<T>
    {
        public ResultStatus status { get; set; }
        public string msg { get; set; }
        public T result { get; set; }
    }

    public enum ResultStatus
    {
        Success = 200,
        BadRequest = 400,
        PermissionDenined = 403,
        FileNotFound = 404,
        Unavailable = 451,
        FileRemoteNotFound = 500,
        BandwidthExceeded = 509
    }
}

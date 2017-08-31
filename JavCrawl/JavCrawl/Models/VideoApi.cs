using Newtonsoft.Json;

namespace JavCrawl.Models
{
    public class VideoApi
    {
        public VideoApi()
        {
            Type = "video/mp4";
        }

        [JsonProperty(PropertyName = "default")]
        public string Default {get;set;}
        [JsonProperty(PropertyName = "label")]
        public string Label { get; set; }
        [JsonProperty(PropertyName = "type")]
        public string Type { get; set; }
        [JsonProperty(PropertyName = "file")]
        public string File { get; set; }
        [JsonProperty(PropertyName = "src")]
        public string Src { get; set; }
    }
}

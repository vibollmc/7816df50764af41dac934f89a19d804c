using System;

namespace JavCrawl.Models.DbEntity
{
    public class JobListCrawl
    {
        public int Id { get; set; }
        public string Link { get; set; }
        public DateTime? ScheduleAt { get; set; }
        public DateTime? StartAt { get; set; }
        public DateTime? FinishAt { get; set; }
        public int Complete { get; set; }
        public int UnComplete { get; set; }
        public sbyte Always { get; set; }
        public int Status { get; set; }
        public string Error { get; set; }
    }
}

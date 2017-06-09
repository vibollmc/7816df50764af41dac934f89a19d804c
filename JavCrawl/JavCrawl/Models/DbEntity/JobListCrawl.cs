using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

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
        public bool Always { get; set; }
    }
}

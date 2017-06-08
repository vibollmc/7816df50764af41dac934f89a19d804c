using System;
using System.Collections.Generic;

namespace JavCrawl.Models.DbEntity
{
    public partial class Films
    {
        public int Id { get; set; }
        public string Calendar { get; set; }
        public int? CategoryId { get; set; }
        public string CoverName { get; set; }
        public DateTime? CreatedAt { get; set; }
        public int? CreatedBy { get; set; }
        public int? CustomerId { get; set; }
        public string Date { get; set; }
        public DateTime? DeletedAt { get; set; }
        public string DemoFilename { get; set; }
        public string DemoSubEn { get; set; }
        public string DemoSubOwn { get; set; }
        public string Description { get; set; }
        public string Episodes { get; set; }
        public string ExistEpisodes { get; set; }
        public string Extend { get; set; }
        public bool? Fixing { get; set; }
        public sbyte? Free { get; set; }
        public int? FtpId { get; set; }
        public sbyte? Hot { get; set; }
        public string ImdbRate { get; set; }
        public string ImdbUrl { get; set; }
        public sbyte? IsNew { get; set; }
        public sbyte? Member { get; set; }
        public sbyte? Online { get; set; }
        public int? Order { get; set; }
        public int? QualityId { get; set; }
        public int? Reported { get; set; }
        public string Runtime { get; set; }
        public string Seo { get; set; }
        public int? ServerId { get; set; }
        public sbyte? Slide { get; set; }
        public string Slug { get; set; }
        public string Storyline { get; set; }
        public string ThumbName { get; set; }
        public string Title { get; set; }
        public string TitleAscii { get; set; }
        public string TitleEn { get; set; }
        public string Trailer { get; set; }
        public DateTime? UpdatedAt { get; set; }
        public int? UpdatedBy { get; set; }
        public int? UserId { get; set; }
        public int? Viewed { get; set; }
    }
}

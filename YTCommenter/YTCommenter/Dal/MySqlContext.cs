using System;
using System.Collections.Generic;
using System.Data.Entity;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using YTCommenter.Models;

namespace YTCommenter.Dal
{
    public class MySqlContext : DbContext
    {
        public virtual DbSet<YoutubeComment> YoutubeComment { get; set; }
        public virtual DbSet<GoogleApi> GoogleApi { get; set; }

        protected override void OnModelCreating(DbModelBuilder modelBuilder)
        {
            modelBuilder.Entity<YoutubeComment>().ToTable("youtube_comment").HasKey(x => x.Id);
            modelBuilder.Entity<YoutubeComment>().Property(x => x.Id).HasColumnName("id").HasColumnType("int(11)");
            modelBuilder.Entity<YoutubeComment>().Property(x => x.VideoId).HasColumnName("videoid").HasColumnType("varchar(45)");
            modelBuilder.Entity<YoutubeComment>().Property(x => x.ChannelId).HasColumnName("channelid").HasColumnType("varchar(45)");
            modelBuilder.Entity<YoutubeComment>().Property(x => x.CreatedAt).HasColumnName("created_at").HasColumnType("datetime");


            modelBuilder.Entity<GoogleApi>().ToTable("google_api").HasKey(x => x.Id);
            modelBuilder.Entity<GoogleApi>().Property(x => x.Id).HasColumnName("id").HasColumnType("int(11)");
            modelBuilder.Entity<GoogleApi>().Property(x => x.Name).HasColumnName("name").HasColumnType("varchar(45)");
            modelBuilder.Entity<GoogleApi>().Property(x => x.ApiKey).HasColumnName("api_key").HasColumnType("varchar(255)");
            modelBuilder.Entity<GoogleApi>().Property(x => x.FileName).HasColumnName("file_name").HasColumnType("varchar(255)");
            modelBuilder.Entity<GoogleApi>().Property(x => x.CreatedAt).HasColumnName("created_at").HasColumnType("datetime");
            modelBuilder.Entity<GoogleApi>().Property(x => x.AuthorizedAt).HasColumnName("authorized_at").HasColumnType("datetime");
            modelBuilder.Entity<GoogleApi>().Property(x => x.LastUsed).HasColumnName("last_used").HasColumnType("datetime");
        }
    }

}

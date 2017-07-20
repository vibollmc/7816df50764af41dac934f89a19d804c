using System;
using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata;
using JavCrawl.Models.DbEntity;

namespace JavCrawl.Dal
{
    public partial class MySqlContext : DbContext
    {
        public virtual DbSet<ArticleTags> ArticleTags { get; set; }
        public virtual DbSet<Articles> Articles { get; set; }
        public virtual DbSet<Bookmarks> Bookmarks { get; set; }
        public virtual DbSet<Categories> Categories { get; set; }
        public virtual DbSet<Countries> Countries { get; set; }
        public virtual DbSet<CustomerTypes> CustomerTypes { get; set; }
        public virtual DbSet<Customers> Customers { get; set; }
        public virtual DbSet<Directors> Directors { get; set; }
        public virtual DbSet<Episodes> Episodes { get; set; }
        public virtual DbSet<Errors> Errors { get; set; }
        public virtual DbSet<Feedbacks> Feedbacks { get; set; }
        public virtual DbSet<Films> Films { get; set; }
        public virtual DbSet<Genres> Genres { get; set; }
        public virtual DbSet<GoogleDriveFiles> GoogleDriveFiles { get; set; }
        public virtual DbSet<GoogleDrives> GoogleDrives { get; set; }
        public virtual DbSet<Messages> Messages { get; set; }
        public virtual DbSet<Payments> Payments { get; set; }
        public virtual DbSet<Qualities> Qualities { get; set; }
        public virtual DbSet<Reporters> Reporters { get; set; }
        public virtual DbSet<Seos> Seos { get; set; }
        public virtual DbSet<Servers> Servers { get; set; }
        public virtual DbSet<Settings> Settings { get; set; }
        public virtual DbSet<Stars> Stars { get; set; }
        public virtual DbSet<Tags> Tags { get; set; }
        public virtual DbSet<UserGroups> UserGroups { get; set; }
        public virtual DbSet<Users> Users { get; set; }
        public virtual DbSet<FilmCountries> FilmCountries { get; set; }
        public virtual DbSet<FilmDirectors> FilmDirectors { get; set; }
        public virtual DbSet<FilmGenres> FilmGenres { get; set; }
        public virtual DbSet<FilmStars> FilmStars { get; set; }
        public virtual DbSet<FilmTags> FilmTags { get; set; }
        public virtual DbSet<Images> Images { get; set; }
        public virtual DbSet<JobListCrawl> JobListCrawl { get; set; }
        public virtual DbSet<YoutubeComment> YoutubeComment { get; set; }
        public virtual DbSet<GoogleApi> GoogleApi { get; set; }

        public MySqlContext(DbContextOptions options) : base(options)
        {

        }
        protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
        {
            //#warning To protect potentially sensitive information in your connection string, you should move it out of source code. See http://go.microsoft.com/fwlink/?LinkId=723263 for guidance on storing connection strings.
            //optionsBuilder.UseMySql(@"Server=localhost;User Id=root;Password=Thuyng@12;Database=jav_new;");
        }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            modelBuilder.Entity<ArticleTags>(entity =>
            {
                entity.ToTable("article_tags");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11) unsigned");

                entity.Property(e => e.ArticleId)
                    .HasColumnName("article_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.TagId)
                    .HasColumnName("tag_id")
                    .HasColumnType("int(11)");
            });

            modelBuilder.Entity<Articles>(entity =>
            {
                entity.ToTable("articles");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Content).HasColumnName("content");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Description)
                    .HasColumnName("description")
                    .HasColumnType("text");

                entity.Property(e => e.Online)
                    .HasColumnName("online")
                    .HasColumnType("tinyint(4)")
                    .HasDefaultValueSql("0");

                entity.Property(e => e.Seo)
                    .HasColumnName("seo")
                    .HasColumnType("text");

                entity.Property(e => e.Slug)
                    .HasColumnName("slug")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Status)
                    .HasColumnName("status")
                    .HasColumnType("tinyint(1)")
                    .HasDefaultValueSql("0");

                entity.Property(e => e.Title)
                    .HasColumnName("title")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.TitleAscii)
                    .HasColumnName("title_ascii")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Type)
                    .HasColumnName("type")
                    .HasColumnType("char(25)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Viewed)
                    .HasColumnName("viewed")
                    .HasColumnType("int(11)");
            });

            modelBuilder.Entity<Bookmarks>(entity =>
            {
                entity.ToTable("bookmarks");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11) unsigned");

                entity.Property(e => e.AbleId)
                    .HasColumnName("able_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Type)
                    .HasColumnName("type")
                    .HasColumnType("char(20)");

                entity.Property(e => e.UserId)
                    .HasColumnName("user_id")
                    .HasColumnType("int(11)");
            });

            modelBuilder.Entity<Categories>(entity =>
            {
                entity.ToTable("categories");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11) unsigned");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Description)
                    .HasColumnName("description")
                    .HasColumnType("text");

                entity.Property(e => e.ParentId)
                    .HasColumnName("parent_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Seo)
                    .HasColumnName("seo")
                    .HasColumnType("text");

                entity.Property(e => e.Slug)
                    .HasColumnName("slug")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Status)
                    .HasColumnName("status")
                    .HasColumnType("tinyint(4)");

                entity.Property(e => e.Title)
                    .HasColumnName("title")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.TitleAscii)
                    .HasColumnName("title_ascii")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");
            });

            modelBuilder.Entity<Countries>(entity =>
            {
                entity.ToTable("countries");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Code)
                    .HasColumnName("code")
                    .HasColumnType("varchar(50)");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Description)
                    .HasColumnName("description")
                    .HasColumnType("text");

                entity.Property(e => e.Menu)
                    .HasColumnName("menu")
                    .HasColumnType("tinyint(2)");

                entity.Property(e => e.Seo)
                    .HasColumnName("seo")
                    .HasColumnType("text");

                entity.Property(e => e.Slug)
                    .HasColumnName("slug")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Status)
                    .HasColumnName("status")
                    .HasColumnType("tinyint(4)");

                entity.Property(e => e.Title)
                    .HasColumnName("title")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.TitleAscii)
                    .HasColumnName("title_ascii")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");
            });

            modelBuilder.Entity<CustomerTypes>(entity =>
            {
                entity.ToTable("customer_types");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11) unsigned");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Slug)
                    .HasColumnName("slug")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Title)
                    .HasColumnName("title")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");
            });

            modelBuilder.Entity<Customers>(entity =>
            {
                entity.ToTable("customers");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11) unsigned");

                entity.Property(e => e.BanedTo)
                    .HasColumnName("baned_to")
                    .HasColumnType("int(11)")
                    .HasDefaultValueSql("0");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Data)
                    .HasColumnName("data")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Email)
                    .HasColumnName("email")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.FullName)
                    .HasColumnName("full_name")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Password)
                    .HasColumnName("password")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Phone)
                    .HasColumnName("phone")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.RememberToken)
                    .HasColumnName("remember_token")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Spamed)
                    .HasColumnName("spamed")
                    .HasColumnType("int(11)")
                    .HasDefaultValueSql("0");

                entity.Property(e => e.Status)
                    .HasColumnName("status")
                    .HasColumnType("tinyint(4)")
                    .HasDefaultValueSql("1");

                entity.Property(e => e.TypeId)
                    .HasColumnName("type_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Username)
                    .HasColumnName("username")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Verified)
                    .HasColumnName("verified")
                    .HasColumnType("tinyint(4)");
            });

            modelBuilder.Entity<Directors>(entity =>
            {
                entity.ToTable("directors");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11) unsigned");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Description)
                    .HasColumnName("description")
                    .HasColumnType("text");

                entity.Property(e => e.Seo)
                    .HasColumnName("seo")
                    .HasColumnType("text");

                entity.Property(e => e.Slug)
                    .HasColumnName("slug")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Status)
                    .HasColumnName("status")
                    .HasColumnType("tinyint(4)");

                entity.Property(e => e.Title)
                    .HasColumnName("title")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.TitleAscii)
                    .HasColumnName("title_ascii")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");
            });

            modelBuilder.Entity<Episodes>(entity =>
            {
                entity.ToTable("episodes");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11) unsigned");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.CustomerId)
                    .HasColumnName("customer_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.FileName)
                    .HasColumnName("file_name")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.FilmId)
                    .HasColumnName("film_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.FtpId)
                    .HasColumnName("ftp_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.ServerId)
                    .HasColumnName("server_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Slug)
                    .HasColumnName("slug")
                    .HasColumnType("char(25)");

                entity.Property(e => e.Status)
                    .HasColumnName("status")
                    .HasColumnType("tinyint(2)");

                entity.Property(e => e.SubEn)
                    .HasColumnName("sub_en")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.SubVi)
                    .HasColumnName("sub_vi")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Title)
                    .HasColumnName("title")
                    .HasColumnType("char(25)");

                entity.Property(e => e.Type)
                    .HasColumnName("type")
                    .HasColumnType("char(25)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.UserId)
                    .HasColumnName("user_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Viewed)
                    .HasColumnName("viewed")
                    .HasColumnType("int(11)");
            });

            modelBuilder.Entity<Errors>(entity =>
            {
                entity.ToTable("errors");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11) unsigned");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Title)
                    .HasColumnName("title")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");
            });

            modelBuilder.Entity<Feedbacks>(entity =>
            {
                entity.ToTable("feedbacks");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11) unsigned");

                entity.Property(e => e.AbleId)
                    .HasColumnName("able_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Description).HasColumnName("description");

                entity.Property(e => e.Reported)
                    .HasColumnName("reported")
                    .HasColumnType("tinyint(4)");

                entity.Property(e => e.Status)
                    .HasColumnName("status")
                    .HasColumnType("char(20)");

                entity.Property(e => e.Title)
                    .HasColumnName("title")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Type)
                    .HasColumnName("type")
                    .HasColumnType("char(20)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.UserId)
                    .HasColumnName("user_id")
                    .HasColumnType("text");
            });

            modelBuilder.Entity<Films>(entity =>
            {
                entity.ToTable("films");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Calendar)
                    .HasColumnName("calendar")
                    .HasColumnType("text");

                entity.Property(e => e.CategoryId)
                    .HasColumnName("category_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.CoverName)
                    .HasColumnName("cover_name")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.CreatedBy)
                    .HasColumnName("created_by")
                    .HasColumnType("int(11)");

                entity.Property(e => e.CustomerId)
                    .HasColumnName("customer_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Date)
                    .HasColumnName("date")
                    .HasColumnType("char(20)");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.DemoFilename)
                    .HasColumnName("demo_filename")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.DemoSubEn)
                    .HasColumnName("demo_sub_en")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.DemoSubOwn)
                    .HasColumnName("demo_sub_own")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Description)
                    .HasColumnName("description")
                    .HasColumnType("text");

                entity.Property(e => e.Episodes)
                    .HasColumnName("episodes")
                    .HasColumnType("char(25)")
                    .HasDefaultValueSql("1");

                entity.Property(e => e.ExistEpisodes)
                    .HasColumnName("exist_episodes")
                    .HasColumnType("char(25)")
                    .HasDefaultValueSql("0");

                entity.Property(e => e.Extend)
                    .HasColumnName("extend")
                    .HasColumnType("text");

                entity.Property(e => e.Fixing)
                    .HasColumnName("fixing")
                    .HasColumnType("tinyint(1)");

                entity.Property(e => e.Free)
                    .HasColumnName("free")
                    .HasColumnType("tinyint(4)")
                    .HasDefaultValueSql("0");

                entity.Property(e => e.FtpId)
                    .HasColumnName("ftp_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Hot)
                    .HasColumnName("hot")
                    .HasColumnType("tinyint(4)");

                entity.Property(e => e.ImdbRate)
                    .HasColumnName("imdb_rate")
                    .HasColumnType("varchar(11)");

                entity.Property(e => e.ImdbUrl)
                    .HasColumnName("imdb_url")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.IsNew)
                    .HasColumnName("is_new")
                    .HasColumnType("tinyint(4)");

                entity.Property(e => e.Member)
                    .HasColumnName("member")
                    .HasColumnType("tinyint(2)");

                entity.Property(e => e.Online)
                    .HasColumnName("online")
                    .HasColumnType("tinyint(4)")
                    .HasDefaultValueSql("0");

                entity.Property(e => e.Order)
                    .HasColumnName("order")
                    .HasColumnType("int(11)");

                entity.Property(e => e.QualityId)
                    .HasColumnName("quality_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Reported)
                    .HasColumnName("reported")
                    .HasColumnType("int(11)")
                    .HasDefaultValueSql("0");

                entity.Property(e => e.Runtime)
                    .HasColumnName("runtime")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Seo)
                    .HasColumnName("seo")
                    .HasColumnType("text");

                entity.Property(e => e.ServerId)
                    .HasColumnName("server_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Slide)
                    .HasColumnName("slide")
                    .HasColumnType("tinyint(4)")
                    .HasDefaultValueSql("0");

                entity.Property(e => e.Slug)
                    .HasColumnName("slug")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Storyline)
                    .HasColumnName("storyline")
                    .HasColumnType("text");

                entity.Property(e => e.ThumbName)
                    .HasColumnName("thumb_name")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Title)
                    .HasColumnName("title")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.TitleAscii)
                    .HasColumnName("title_ascii")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.TitleEn)
                    .HasColumnName("title_en")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Trailer)
                    .HasColumnName("trailer")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.UpdatedBy)
                    .HasColumnName("updated_by")
                    .HasColumnType("int(11)");

                entity.Property(e => e.UserId)
                    .HasColumnName("user_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Viewed)
                    .HasColumnName("viewed")
                    .HasColumnType("int(11)")
                    .HasDefaultValueSql("0");
            });

            modelBuilder.Entity<Genres>(entity =>
            {
                entity.ToTable("genres");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Description)
                    .HasColumnName("description")
                    .HasColumnType("text");

                entity.Property(e => e.Menu)
                    .HasColumnName("menu")
                    .HasColumnType("tinyint(2)");

                entity.Property(e => e.ParentId)
                    .HasColumnName("parent_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Seo)
                    .HasColumnName("seo")
                    .HasColumnType("text");

                entity.Property(e => e.Slug)
                    .IsRequired()
                    .HasColumnName("slug")
                    .HasColumnType("varchar(255)")
                    .HasDefaultValueSql("0");

                entity.Property(e => e.Status)
                    .HasColumnName("status")
                    .HasColumnType("tinyint(4)")
                    .HasDefaultValueSql("1");

                entity.Property(e => e.Title)
                    .IsRequired()
                    .HasColumnName("title")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.TitleAscii)
                    .HasColumnName("title_ascii")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");
            });

            modelBuilder.Entity<GoogleDriveFiles>(entity =>
            {
                entity.ToTable("google_drive_files");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11) unsigned");

                entity.Property(e => e.AbleId)
                    .HasColumnName("able_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.FileId)
                    .HasColumnName("file_id")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.FileName)
                    .HasColumnName("file_name")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.FilmVideoId)
                    .HasColumnName("film_video_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Status)
                    .HasColumnName("status")
                    .HasColumnType("char(20)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");
            });

            modelBuilder.Entity<GoogleDrives>(entity =>
            {
                entity.ToTable("google_drives");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11) unsigned");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.FolderId)
                    .HasColumnName("folder_id")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.FolderName)
                    .HasColumnName("folder_name")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Status)
                    .HasColumnName("status")
                    .HasColumnType("char(20)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");
            });

            modelBuilder.Entity<Messages>(entity =>
            {
                entity.ToTable("messages");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11) unsigned");

                entity.Property(e => e.Content).HasColumnName("content");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.CustomerId)
                    .HasColumnName("customer_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Status)
                    .HasColumnName("status")
                    .HasColumnType("tinyint(2)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");
            });

            modelBuilder.Entity<Payments>(entity =>
            {
                entity.ToTable("payments");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11) unsigned");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.CustomerId)
                    .HasColumnName("customer_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Data)
                    .HasColumnName("data")
                    .HasColumnType("text");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Price)
                    .HasColumnName("price")
                    .HasColumnType("decimal(10,0)");

                entity.Property(e => e.Status)
                    .HasColumnName("status")
                    .HasColumnType("char(25)");

                entity.Property(e => e.TimePay)
                    .HasColumnName("time_pay")
                    .HasColumnType("int(11)");

                entity.Property(e => e.TimePending)
                    .HasColumnName("time_pending")
                    .HasColumnType("int(11)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Viewed)
                    .HasColumnName("viewed")
                    .HasColumnType("int(11)");
            });

            modelBuilder.Entity<Qualities>(entity =>
            {
                entity.ToTable("qualities");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Description)
                    .HasColumnName("description")
                    .HasColumnType("text");

                entity.Property(e => e.FtpId)
                    .HasColumnName("ftp_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.QualityFlag)
                    .HasColumnName("quality_flag")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Seo)
                    .HasColumnName("seo")
                    .HasColumnType("text");

                entity.Property(e => e.Slug)
                    .HasColumnName("slug")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Status)
                    .HasColumnName("status")
                    .HasColumnType("tinyint(2)");

                entity.Property(e => e.Title)
                    .HasColumnName("title")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.TitleAscii)
                    .HasColumnName("title_ascii")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");
            });

            modelBuilder.Entity<Reporters>(entity =>
            {
                entity.ToTable("reporters");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11) unsigned");

                entity.Property(e => e.AbleId)
                    .HasColumnName("able_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Content)
                    .HasColumnName("content")
                    .HasColumnType("text");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.CustomerId)
                    .HasColumnName("customer_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.ErrorId)
                    .HasColumnName("error_id")
                    .HasColumnType("int(11)")
                    .HasDefaultValueSql("1");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");
            });

            modelBuilder.Entity<Seos>(entity =>
            {
                entity.ToTable("seos");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11) unsigned");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Description)
                    .HasColumnName("description")
                    .HasColumnType("text");

                entity.Property(e => e.Keyword)
                    .HasColumnName("keyword")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Slug)
                    .HasColumnName("slug")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Title)
                    .HasColumnName("title")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");
            });

            modelBuilder.Entity<Servers>(entity =>
            {
                entity.ToTable("servers");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(10) unsigned");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Data)
                    .HasColumnName("data")
                    .HasColumnType("text");

                entity.Property(e => e.Default)
                    .HasColumnName("default")
                    .HasColumnType("tinyint(4)")
                    .HasDefaultValueSql("0");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Description)
                    .HasColumnName("description")
                    .HasColumnType("text");

                entity.Property(e => e.Status)
                    .HasColumnName("status")
                    .HasColumnType("tinyint(4)");

                entity.Property(e => e.Title)
                    .HasColumnName("title")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.TitleAscii)
                    .HasColumnName("title_ascii")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Type)
                    .HasColumnName("type")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");
            });

            modelBuilder.Entity<Settings>(entity =>
            {
                entity.ToTable("settings");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11) unsigned");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Data)
                    .HasColumnName("data")
                    .HasColumnType("text");

                entity.Property(e => e.DataType)
                    .HasColumnName("data_type")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Location)
                    .HasColumnName("location")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Status)
                    .HasColumnName("status")
                    .HasColumnType("tinyint(4)");

                entity.Property(e => e.Title)
                    .HasColumnName("title")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Type)
                    .HasColumnName("type")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");
            });

            modelBuilder.Entity<Stars>(entity =>
            {
                entity.ToTable("stars");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11) unsigned");

                entity.Property(e => e.Birth)
                    .HasColumnName("birth")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.FtpId)
                    .HasColumnName("ftp_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Fullname)
                    .HasColumnName("fullname")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Height)
                    .HasColumnName("height")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.HomeTown)
                    .HasColumnName("home_town")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Hot)
                    .HasColumnName("hot")
                    .HasColumnType("tinyint(4)");

                entity.Property(e => e.Seo)
                    .HasColumnName("seo")
                    .HasColumnType("text");

                entity.Property(e => e.Slug)
                    .HasColumnName("slug")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Story)
                    .HasColumnName("story")
                    .HasColumnType("text");

                entity.Property(e => e.ThumbName)
                    .HasColumnName("thumb_name")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Title)
                    .HasColumnName("title")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.TitleAscii)
                    .HasColumnName("title_ascii")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");
            });

            modelBuilder.Entity<Tags>(entity =>
            {
                entity.ToTable("tags");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Seo)
                    .HasColumnName("seo")
                    .HasColumnType("text");

                entity.Property(e => e.Slug)
                    .HasColumnName("slug")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Status)
                    .HasColumnName("status")
                    .HasColumnType("tinyint(1)");

                entity.Property(e => e.Title)
                    .HasColumnName("title")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.TitleAscii)
                    .HasColumnName("title_ascii")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");
            });

            modelBuilder.Entity<UserGroups>(entity =>
            {
                entity.ToTable("user_groups");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Editble)
                    .HasColumnName("editble")
                    .HasColumnType("tinyint(4)")
                    .HasDefaultValueSql("1");

                entity.Property(e => e.Title)
                    .HasColumnName("title")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");
            });

            modelBuilder.Entity<Users>(entity =>
            {
                entity.ToTable("users");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Address)
                    .HasColumnName("address")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Avatar)
                    .HasColumnName("avatar")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Email)
                    .HasColumnName("email")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.FtpId)
                    .HasColumnName("ftp_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.FullName)
                    .HasColumnName("full_name")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.GroupId)
                    .HasColumnName("group_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Password)
                    .HasColumnName("password")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.PhoneNumber)
                    .HasColumnName("phone_number")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.RoleId)
                    .HasColumnName("role_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Status)
                    .HasColumnName("status")
                    .HasColumnType("tinyint(4)");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Username)
                    .HasColumnName("username")
                    .HasColumnType("varchar(255)");
            });

            modelBuilder.Entity<FilmCountries>(entity =>
            {
                entity.ToTable("film_countries");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.FilmId)
                    .HasColumnName("film_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.CountryId)
                    .HasColumnName("country_id")
                    .HasColumnType("int(11)");
            });

            modelBuilder.Entity<FilmDirectors>(entity =>
            {
                entity.ToTable("film_directors");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.FilmId)
                    .HasColumnName("film_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.DirectorId)
                    .HasColumnName("director_id")
                    .HasColumnType("int(11)");
            });

            modelBuilder.Entity<FilmGenres>(entity =>
            {
                entity.ToTable("film_genres");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.FilmId)
                    .HasColumnName("film_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.GenreId)
                    .HasColumnName("genre_id")
                    .HasColumnType("int(11)");
            });

            modelBuilder.Entity<FilmStars>(entity =>
            {
                entity.ToTable("film_stars");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.FilmId)
                    .HasColumnName("film_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.StarId)
                    .HasColumnName("star_id")
                    .HasColumnType("int(11)");
            });

            modelBuilder.Entity<FilmTags>(entity =>
            {
                entity.ToTable("film_tags");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.FilmId)
                    .HasColumnName("film_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.TagId)
                    .HasColumnName("tag_id")
                    .HasColumnType("int(11)");
            });

            modelBuilder.Entity<Images>(entity =>
            {
                entity.ToTable("images");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.IdArticle)
                    .HasColumnName("id_article")
                    .HasColumnType("int(11)");

                entity.Property(e => e.ItemId)
                    .HasColumnName("item_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.ServerId)
                    .HasColumnName("server_id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.FileName)
                    .HasColumnName("filename")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Link)
                    .HasColumnName("link")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.Type)
                    .HasColumnName("type")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.UpdatedAt)
                    .HasColumnName("updated_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.DeletedAt)
                    .HasColumnName("deleted_at")
                    .HasColumnType("datetime");
            });

            modelBuilder.Entity<JobListCrawl>(entity =>
            {
                entity.ToTable("job_list_crawl");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(10)");

                entity.Property(e => e.Link)
                    .HasColumnName("link")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.ScheduleAt)
                    .HasColumnName("schedule_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.StartAt)
                    .HasColumnName("start_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.FinishAt)
                    .HasColumnName("finish_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.Complete)
                    .HasColumnName("complete")
                    .HasColumnType("int(11)");

                entity.Property(e => e.UnComplete)
                    .HasColumnName("uncomplete")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Always)
                    .HasColumnName("always")
                    .HasColumnType("tinyint(1)");

                entity.Property(e => e.Status)
                    .HasColumnName("status")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Error)
                    .HasColumnName("error")
                    .HasColumnType("varchar(1024)");
            });

            modelBuilder.Entity<YoutubeComment>(entity =>
            {
                entity.ToTable("youtube_comment");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.VideoId)
                    .HasColumnName("videoid")
                    .HasColumnType("varchar(45)");

                entity.Property(e => e.ChannelId)
                    .HasColumnName("channelid")
                    .HasColumnType("varchar(45)");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");
            });

            modelBuilder.Entity<GoogleApi>(entity =>
            {
                entity.ToTable("google_api");

                entity.Property(e => e.Id)
                    .HasColumnName("id")
                    .HasColumnType("int(11)");

                entity.Property(e => e.Name)
                    .HasColumnName("name")
                    .HasColumnType("varchar(45)");

                entity.Property(e => e.ApiKey)
                    .HasColumnName("api_key")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.FileName)
                    .HasColumnName("file_name")
                    .HasColumnType("varchar(255)");

                entity.Property(e => e.CreatedAt)
                    .HasColumnName("created_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.AuthorizedAt)
                    .HasColumnName("authorized_at")
                    .HasColumnType("datetime");

                entity.Property(e => e.LastUsed)
                    .HasColumnName("last_used")
                    .HasColumnType("datetime");
            });
        }
    }
}
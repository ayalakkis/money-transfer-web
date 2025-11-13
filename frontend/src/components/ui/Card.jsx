import clsx from 'clsx'

const Card = ({ children, className, title, subtitle, action, icon }) => {
  return (
    <div className={clsx('bg-white rounded-lg shadow-sm border border-[#dbdade] hover:shadow-md transition-all', className)}>
      {(title || action || icon) && (
        <div className="px-6 py-5 border-b border-[#dbdade] flex items-start justify-between">
          <div className="flex items-start space-x-4">
            {icon && (
              <div className="flex-shrink-0">
                {icon}
              </div>
            )}
            <div>
              {title && <h3 className="text-[17px] font-semibold text-[#566a7f] mb-1">{title}</h3>}
              {subtitle && <p className="text-sm text-[#a1acb8]">{subtitle}</p>}
            </div>
          </div>
          {action && <div className="flex-shrink-0">{action}</div>}
        </div>
      )}
      <div className="p-6">{children}</div>
    </div>
  )
}

export default Card
